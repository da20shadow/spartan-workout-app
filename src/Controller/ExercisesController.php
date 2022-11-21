<?php

namespace App\Controller;

use App\Entity\Exercises;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExercisesController extends AbstractController
{
    #[Route('/api/exercises/history', name: 'app_exercises')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $this->getUser()->getId();
//        $result = $doctrine
//            ->getRepository(Exercises::class)
//            ->findAll();

        $dateNow = date('Y-m-d');
        $dateHistory = date('Y-m-d', strtotime('-30 days'));

        $result = $doctrine
            ->getRepository(Exercises::class)
            ->findFromDate($dateHistory,$userId);

        if (count($result) < 1){
            return $this->json([
                'message' => 'No Exercises History!',
            ]);
        }

        $workoutsList = [];
        foreach ($result as $workout) {

            $date = date('Y-m-d', strtotime($workout['workout_time']));

            array_push($workoutsList,[
                'id' => $workout['id'],
                'user_id' => $workout['user_id'],
                'push_ups' => $workout['push_ups'],
                'sit_ups' => $workout['sit_ups'],
                'bench_dips' => $workout['bench_dips'],
                'squats' => $workout['squats'],
                'pull_ups' => $workout['pull_ups'],
                'hammer_curl' => $workout['hammer_curl'],
                'barbel_curl' => $workout['barbel_curl'],
                'workout_time' => $date
            ]);

        }
        return $this->json($workoutsList);
    }

    #[Route('/api/exercises', name: 'app_history')]
    public function history(ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $dateNow = date('Y-m-d');

        $result = $doctrine
            ->getRepository(Exercises::class)
            ->findTodayWorkouts();

        if (!$result){
            return $this->json([
                'message' => 'No Exercises Today!',
            ],404);
        }

        return $this->json($result);
    }

    #[Route('/api/add/workout', name: 'app_workout')]
    public function addWorkout(Request $request,
                               ManagerRegistry $doctrine): JsonResponse
    {
        $inputs = json_decode($request->getContent(),true);

        if (!isset($inputs['workoutType']) || !isset($inputs['reps'])){
            return $this->json(['message' => 'Not set workout type!'],400);
        }

        $workoutType = $inputs['workoutType'];
        $reps = $inputs['reps'];

        $entityManager = $doctrine->getManager();

        $todayWorkout = $entityManager
            ->getRepository(Exercises::class)
            ->findTodayWorkouts();
        $currentUser = $entityManager
            ->getRepository(User::class)
            ->find($this->getUser()->getId());


        if (!$todayWorkout) {
            $exercise = new Exercises();
            $date = \DateTime::createFromFormat('Y-m-d',date('Y-m-d'));
            $exercise->setWorkoutTime($date);
            $exercise->setUserId($currentUser);

            switch ($workoutType){
                case "push_ups":
                    $exercise->setPushUps($reps);
                    break;
                case "sit_ups":
                    $exercise->setSitUps($reps);
                    break;
                case "squats":
                    $exercise->setSquats($reps);
                    break;
                case "bench_dips":
                    $exercise->setBenchDips($reps);
                    break;
                case "pull_ups":
                    $exercise->setPullUps($reps);
                    break;
                case "hammer_curl":
                    $exercise->setHammerCurl($reps);
                    break;
                case "barbel_curl":
                    $exercise->setBarbelCurl($reps);
                    break;
            }
            $entityManager->persist($exercise);
            $entityManager->flush();
            return $this->json(['message' => 'First workout added!!']);
        }

        $workout_id = $todayWorkout['id'];
        $todayWorkout = $entityManager
            ->getRepository(Exercises::class)
            ->find($workout_id);

        switch ($workoutType){
            case "push_ups":
                $currentPushUps = $todayWorkout->getPushUps();
                $todayWorkout->setPushUps($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Push up reps!']);
            case "sit_ups":
                $currentPushUps = $todayWorkout->getSitUps();
                $todayWorkout->setSitUps($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Sit up reps!']);
            case "squats":
                $currentPushUps = $todayWorkout->getSquats();
                $todayWorkout->setSquats($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Squat reps!']);
            case "bench_dips":
                $currentPushUps = $todayWorkout->getBenchDips();
                $todayWorkout->setBenchDips($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Bench Dip reps!']);
            case "pull_ups":
                $currentPushUps = $todayWorkout->gePullUps();
                $todayWorkout->setPullUps($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Pull up reps!']);
            case "hammer_curl":
                $currentPushUps = $todayWorkout->getHammerCurl();
                $todayWorkout->setHammerCurl($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Hammer Curl reps!']);
            case "barbel_curl":
                $currentPushUps = $todayWorkout->getBarbelCurl();
                $todayWorkout->setBarbelCurl($currentPushUps + $reps);
                $entityManager->flush();
                return $this->json(['message' => 'Successfully Added ' . $reps . ' Barbel Curl reps!']);
        }
        return $this->json(['error' => 'An Error occur! Please try again!'],401);
    }
}
