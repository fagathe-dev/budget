<?php 
namespace App\Controller\Admin;

use App\Mailer\Email;
use App\Mailer\MailerEnum;
use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/email', name: 'admin_mailing_')]
class MailingController extends AbstractController
{

    public function __construct() {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Liste des e-mails', $this->generateUrl('admin_mailing_index')),
        ]);
        $emails = MailerEnum::getEmails();

        return $this->render('admin/emails/index.html.twig', compact('breadcrumb', 'emails'));
    }

    #[Route('/view', name: 'view', methods: ['GET'])]
    public function show(Request $request):Response 
    {
        $query = 'email';
        $param = $request->query->get($query);
        $email = MailerEnum::getEmail($param);

        if (!array_key_exists($query, $request->query->all())) {
            throw $this->createNotFoundException(sprintf("Il semble que vous avez oublié de passer le paramètre %s dans l'url.", $query));
        }

        if ($email instanceof Email) {
            $label = $email->getLabel();
            $template = $email->getTemplate();
            $breadcrumb = new Breadcrumb([
                new BreadcrumbItem('Liste des e-mails', $this->generateUrl('admin_mailing_index')),
                new BreadcrumbItem($label, $this->generateUrl('admin_mailing_index')),
            ]);

            return $this->render('admin/emails/show.html.twig', array_merge(
                $email->getData(), 
                compact('label', 'template', 'breadcrumb')
            ));
        } else {
            throw $this->createNotFoundException(sprintf("L'email '%s', que vous avez demandé est introuvable.", $param));
        }
    }

}