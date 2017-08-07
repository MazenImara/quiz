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
		$form                  = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addAnswerForm');
		$deleteForm            = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteAnswerForm');
		$editForm              = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editQuestionForm');
		$answerStatusForm      = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\changeAnswerStatusForm');
		$answerStatusMultiForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\changeAnswerStatusMultiForm');
		return array(
			'#theme'                 => 'question',
			'#content'               => [
				'question'              => quizMethods::getQuestionById($id),
				'form'                  => $form,
				'deleteForm'            => $deleteForm,
				'editForm'              => $editForm,
				'answers'               => quizMethods::getAllAnswers($id),
				'answerStatusForm'      => $answerStatusForm,
				'answerStatusMultiForm' => $answerStatusMultiForm,
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
				'userTries'    => quizMethods::getTries($id),
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
		$quiz = quizMethods::getQuiz($id);
		session_start();
		$_SESSION['tryId'] = quizMethods::addTry($_SESSION['login_user']['id'], $quiz['title']);
		return array(
			'#theme'    => 'start_quiz',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content' => [
				'user'    => $_SESSION['login_user'],
				'quiz'    => $quiz,
			],
		);
	}

	public function results() {

		return array(
			'#theme'    => 'results',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content' => [
				'tries'   => quizMethods::getTries(),

			],
		);
	}
	public function result($tryId) {

		return array(
			'#theme'    => 'result',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content' => [
				'results' => quizMethods::getResult($tryId),
				'try'     => quizMethods::getTry($tryId),

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
			if ($_POST['questionId'] != '0') {
				quizMethods::result($_SESSION['tryId'], $_POST['quizId'], $_POST['questionId'], $_POST['userAnswer']);
			}
			if ($nextQuestion = quizMethods::getNextQuestion($_POST['questionId'], $_POST['quizId'])) {
				$questionWithAnswer = [
					'question' => $nextQuestion,
					'answers'  => quizMethods::getAllAnswers($nextQuestion['id']),
				];

				return new JsonResponse($questionWithAnswer);
			} else {
				$result = quizMethods::getResult($_SESSION['tryId']);
				return new JsonResponse($result);
			}

		}
	}

}