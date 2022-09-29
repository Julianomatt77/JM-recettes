<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/account')]
#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this -> userRepository = $userRepository;
        $this -> security = $security;
    }

    #[Route('/{id}', name: 'app_account', methods: ['GET'])]
    public function index(User $user): Response
    {        
        $connectedUser = $this->security->getUser();

        //Empêche un utilisateur à accéder aux infos de compte d'un autre compte que le sien
        if ($user->getId() == $connectedUser->getId()){
            return $this->render('account/index.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    #[Route('/{id}/edit', name: 'account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher,UserAuthenticatorInterface $userAuthenticator): Response
    {
        $connectedUser = $this->security->getUser();

        //Empêche un utilisateur à accéder aux infos de compte d'un autre compte que le sien
        if ($user->getId() == $connectedUser->getId()) {
            $form = $this -> createForm(RegistrationFormType::class, $user);
            $form -> handleRequest($request);

            if ($form -> isSubmitted() && $form -> isValid()) {

                // encode the plain password
                $user -> setPassword(
                    $userPasswordHasher -> hashPassword(
                        $user,
                        $form -> get('plainPassword') -> getData()
                    )
                );

                // J'enregistre mes nouvelles infos
                $entityManager -> flush();

                return $this -> redirectToRoute('app_account', ['id' => $user -> getId()], Response::HTTP_SEE_OTHER);
            }


            $data = $form -> getData();
            return $this -> render('account/edit.html.twig', [
                'user' => $user,
                'registrationForm' => $form -> createView(),
                'data' => $data,
            ]);
        }

        return $this->redirectToRoute('default');
    }

    #[Route('/{id}/delete', name: 'account_delete', methods: ['GET','POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $connectedUser = $this->security->getUser();
        
        //Empêche un utilisateur à accéder aux infos de compte d'un autre compte que le sien
        if ($user->getId() == $connectedUser->getId()) {

            if ($this -> isCsrfTokenValid('delete' . $user -> getId(), $request -> request -> get('_token'))) {
                
                $session = new Session();
                $session->invalidate();

                $userRepository->remove($user, true);
            }
           
            return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
    }
}
