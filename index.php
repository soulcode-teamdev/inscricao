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
 * Index page for the Inscrição plugin
 *
 * @package     local_inscricao
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/inscricao/lib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/inscricao/index.php'));
$PAGE->set_pagelayout('login');
$PAGE->set_title(get_string('pluginname', 'local_inscricao'));

$inscricaoform = new \local_inscricao\form\form();

$email = optional_param('email', '', PARAM_EMAIL);

$inscricaoform->set_data(['email' => $email]);

if ($data = $inscricaoform->get_data()) {
    echo inscricao_update_password($data);
} else {
    $inscricaoform->set_data($toform);
}

echo $OUTPUT->header();
echo '<h2 class="mb-5">Crie sua senha:</h2>';
$inscricaoform->display();
echo $OUTPUT->footer();
