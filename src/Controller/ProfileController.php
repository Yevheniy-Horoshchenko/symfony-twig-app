<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'get_profile', methods: [Request::METHOD_GET])]
    public function getProfile(): Response
    {
        /** @var ?User */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user->getProfile());

        return $this->render('profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile', name: 'update_profile', methods: [Request::METHOD_POST])]
    public function updateProfile(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        /** @var ?User */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user->getProfile());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $userImage */
            $userImage = $form->get('image')->getData();

            $imageName = uniqid() . '.' . $userImage->guessExtension();

            $userImage->move($this->getParameter('uploads_path'), $imageName);

            $user->getProfile()->setImage($imageName);

            $entityManager->flush();

            return $this->redirectToRoute('get_profile');
        }

        return $this->render('profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
