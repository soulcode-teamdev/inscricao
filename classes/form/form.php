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
 * Formulário de Inscrição
 *
 * @package     local_inscricao
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_inscricao\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/lib/moodlelib.php');

/**
 * Form for signup.
 *
 * This class defines a form for signup.
 *
 * @package     local_inscricao
 */
class form extends \moodleform {
    /**
     * Define the form.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'email', '');
        $mform->setType('email', PARAM_EMAIL);

        $mform->addElement('password', 'password', get_string('password', 'local_inscricao'), ['class' => 'form-group-inscricao']);
        $mform->setType('password', PARAM_RAW);

        $mform->addElement('password', 'confirmpassword', get_string('confirmpassword', 'local_inscricao'), ['class' => 'form-group-inscricao']);
        $mform->setType('confirmpassword', PARAM_RAW);

        $mform->addElement('submit', 'submitmessage', get_string('submit'), ['class' => 'flex-column']);
    }

    /**
     * Validates the form data.
     *
     * @param array $data Form data.
     * @param array $files Uploaded files.
     * @return array List of errors, empty if no errors.
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        if (empty($data['password'])) {
            $errors['password'] = get_string('requiredpassword', 'local_inscricao');
        }

        $errmsg = '';

        if (!check_password_policy($data['password'],  $errmsg)) {
            $errors['password'] = $errmsg;
        }

        if ($data['password'] !== $data['confirmpassword']) {
            $errors['confirmpassword'] = get_string('passwordmismatch', 'local_inscricao');
        }

        return $errors;
    }
}
