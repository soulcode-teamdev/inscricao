<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Lib for the Inscrição plugin
 *
 * @package     local_inscricao
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/lib/moodlelib.php');

/**
 * Faz o update da senha e chama as funções para gerar a sessão.
 *
 * @param stdClass $userdata O objeto com dados do usuario.
 * @return int|false O ID do novo usuário ou false em caso de falha.
 */
function inscricao_update_password($userdata) {
    global $DB;

    $user = $DB->get_record('user', ['email' => $userdata->email]);

    try {
        update_internal_user_password($user, $userdata->password);
        inscricao_create_user_preference($user->id);
        inscricao_generate_session($user->id);
    } catch (Exception $e) {
        throw new Exception('Erro ao atualizar a senha: ' . $e->getMessage());
    }

    return $user->id;
}

/**
 * Creates or updates the 'auth_emailconfirmed' user preference for the specified user ID.
 *
 * @param int $userid The ID of the user.
 */
function inscricao_create_user_preference($userid) {
    global $DB;
    if ($DB->record_exists('user_preferences', ['userid' => $userid, 'name' => 'auth_emailconfirmed'])) {
        $DB->set_field('user_preferences', 'value', '1', ['userid' => $userid, 'name' => 'auth_emailconfirmed']);
    } else {
        $preference = new stdClass();
        $preference->userid = $userid;
        $preference->name = 'auth_emailconfirmed';
        $preference->value = '1';
        $DB->insert_record('user_preferences', $preference);
    }
}

/**
 * Generates a session for the specified user ID and logs the user in.
 *
 * @param int $userid The ID of the user to generate a session for.
 */
function inscricao_generate_session($userid) {
    global $DB;
    $user = $DB->get_record('user', ['id' => $userid]);
    complete_user_login($user);

    redirect(new moodle_url('/'));
}
