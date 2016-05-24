<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegistrationController
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        //Création du formulaire
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        //Gérer le submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //Si le contenue du formulaire est valide et soumis, encodage du mot de passe
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            //Enregistrement de l'utilisateur
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Congratulations ! Your account has been successfully created.');
            return $this->redirectToRoute('homepage');

        }
        return $this->render('security/register.html.twig', array('form' => $form->createView()));
    }
}
