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
		$agreementLinkForm =  \Drupal::formBuilder()->getForm('Drupal\quiz\Form\agreementLinkForm');
		return array(
			'#theme'      => 'main',
			'#content'    => [
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'quizes'     => quizMethods::getAllQuizes(),
				'agreementLinkForm' => $agreementLinkForm,
			],
		);
	}
	public function quiz($id) {
		return array(
			'#theme'        => 'quiz',
			'#content'      => [
				'quiz'         => quizMethods::getQuiz($id),
				'form'         => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuestionForm', $id),
				'deleteForm'   => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteQuestionForm'),
				'questions'    => quizMethods::getAllQuestions($id),
				'editQuizForm' => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editQuizForm'),
			],
		);
	}

	public function question($id) {
		$form                  = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addAnswerForm');
		$deleteForm            = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteAnswerForm');
		$editForm              = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editQuestionForm', $id);
		$answerStatusForm      = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\changeAnswerStatusForm');
		$answerStatusMultiForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\changeAnswerStatusMultiForm');
		$addFieldForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addTextFieldForm', $id);
		$deleteTextFieldForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteTextFieldForm');
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
				'addFieldForm' => $addFieldForm,
				'textFields' => quizMethods::getTextFields($id),
				'deleteTextFieldForm' => $deleteTextFieldForm,
			],
		);
	}

	public function quizUsers() {
		return array(
			'#theme'          => 'quiz_users',
			'#content'        => [
				'users'          => quizMethods::getAllUsers(),
				'form'           => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addUserForm'),
				'deleteUserForm' => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteUserForm'),
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
				'delTryForm'   => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteTryForm'),
			],
		);
	}

	public function userQuiz() {
		$user = null;
		quizMethods::timeout();
		if (isset($_SESSION['login_user'])) {
			$user = $_SESSION['login_user'];
		}
		$quizzes = quizMethods::getUserQuizes($user['id']);
		if (isset($_SESSION['login_user']) && count($quizzes) < 2) {
			$response = new RedirectResponse('/startquiz/'.$quizzes[0]['id']);
			$response->send();
		}
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
				'userQuizes' => $quizzes,
			],
		);
	}

	public function startQuiz($id) {
		quizMethods::timeout();
		$user = null;
		if (!isset($_SESSION['login_user'])) {
			$response = new RedirectResponse('/webkurs');
			$response->send();
		} else {
			$user              = $_SESSION['login_user'];
			$quiz              = quizMethods::getQuiz($id);
			$_SESSION['tryId'] = quizMethods::addTry($user['id'], $quiz['title'], $quiz['seccess']);
		}

		return array(
			'#theme'    => 'start_quiz',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content' => [
				'user'    => $user,
				'quiz'    => $quiz,
				'lang'    => \Drupal::languageManager()->getCurrentLanguage()->getId(),
				'agreementLink' => \Drupal::config('quiz.settings')->get('agreement_link'),
			],
		);
	}

	public function results() {
		quizMethods::deleteNullScoreTries();
		return array(
			'#theme'    => 'results',
			'#attached' => [
				'library'  => [
					'quiz/quiz_lib',
				],
			],
			'#content'    => [
				'tries'      => quizMethods::getTries(),
				'delTryForm' => \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteTryForm'),

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
			unset($_SESSION['timeout']);
		}
		$response = new RedirectResponse('/webkurs');
		$response->send();
		return [
			'#type'   => 'markup',
			'#markup' => $this->t('Hello, World!'),
		];
	}

	public function ajaxQuiz() {
		quizMethods::timeout();
		if (!isset($_SESSION['login_user'])) {
			return new JsonResponse(['login' => '0']);
		} else {
			if ($_POST['questionId'] != '0') {
				$userAnswer = null;
				if (isset($_POST['userAnswer'])) {
					$userAnswer = $_POST['userAnswer'];
				}
				quizMethods::result($_SESSION['tryId'], $_POST['quizId'], $_POST['questionId'], $userAnswer);
			}
			if ($nextQuestion = quizMethods::getNextQuestion($_POST['questionId'], $_POST['quizId'])) {
				if ($nextQuestion['textChoice']) {
					$answers = quizMethods::getTextFields($nextQuestion['id']);
				}
				else{					
					$answers = quizMethods::getAllAnswers($nextQuestion['id']);
				}
				$questionWithAnswer = [
					'question' => $nextQuestion,
					'answers'  => $answers,
				];

				return new JsonResponse($questionWithAnswer);
			} else {
				
				$result = quizMethods::getResult($_SESSION['tryId']);
				quizMethods::sendResult($result, $_POST['quizId'], $_SESSION['tryId']);
				return new JsonResponse($result);
			}

		}
	}

	public function ajaxAddTryDetails() {
		$details = [
			'try_name'  => $_POST['try_name'],
			'try_email' => $_POST['try_email'],
			'try_shop'  => $_POST['try_shop'],
		];

		quizMethods::addTryDetails($details);
		return new JsonResponse([]);
	}

}