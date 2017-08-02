<?php

namespace Drupal\quiz\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\quiz\Classes\quizMethods;

class quizController extends ControllerBase {

	/**
	 * Display the markup.
	 *
	 * @return array
	 */
	public function main() {
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuizForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteQuizForm');
		return array(
			'#theme'      => 'main',
			'#content'    => [
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'quizes'     => quizMethods::getAllQuizes(),
			],
		);
	}
	public function quiz($id) {
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuestionForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteQuestionForm');

		return array(
			'#theme'      => 'quiz',
			'#content'    => [
				'quiz'       => quizMethods::getQuiz($id),
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'questions'  => quizMethods::getAllQuestions($id),
			],
		);
	}

	public function question($id) {
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addAnswerForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteAnswerForm');
		$editForm   = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editQuestionForm');
		return array(
			'#theme'      => 'question',
			'#content'    => [
				'question'   => quizMethods::getQuestionById($id),
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'editForm'   => $editForm,
				'answers'    => quizMethods::getAllAnswers($id),
			],
		);
	}

	public function quizUsers() {
		$form = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addUserForm');
		return array(
			'#theme'   => 'quiz_users',
			'#content' => [
				'users'   => quizMethods::getAllUsers(),
				'form'    => $form,

			],
		);
	}

	public function quizUser($id) {

		return array(
			'#theme'        => 'quiz_user',
			'#content'      => [
				'user'         => quizMethods::getUser($id),
				'quizes'       => quizMethods::getAllQuizes(),
				'userQuizes'   => quizMethods::getUserQuizes($id),
				'assignForm'   => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\assignQuizForm'),
				'unAssignForm' => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\unAssignQuizForm'),
				'editForm'     => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editUserForm'),
			],
		);
	}

	public function userQuiz() {
		$user = $_SESSION['login_user'];
		return array(
			'#theme'    => 'user_quiz',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content'    => [
				'loginForm'  => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\loginForm'),
				'user'       => $user,
				'userQuizes' => quizMethods::getUserQuizes($user['id']),
			],
		);
	}

	public function startQuiz($id) {
		if (!isset($_SESSION['login_user'])) {
			$response = new RedirectResponse('/userquiz');
			$response->send();
		}
		return array(
			'#theme'    => 'start_quiz',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content' => [
				'user'    => $_SESSION['login_user'],
				'quiz'    => quizMethods::getQuiz($id),
			],
		);
	}

	public function logout() {
		if (isset($_SESSION['login_user'])) {
			unset($_SESSION['login_user']);
		}
		$response = new RedirectResponse('/');
		$response->send();
	}

	public function ajaxQuiz() {
		if (!isset($_SESSION['login_user'])) {
			return new JsonResponse(['login' => '0']);
		} else {
			if ($nextQuestion = quizMethods::getNextQuestion($_POST['questionId'], $_POST['quizId'])) {
				return new JsonResponse($nextQuestion);
			} else {
				return new JsonResponse(['more' => '0']);
			}
		}
	}

}