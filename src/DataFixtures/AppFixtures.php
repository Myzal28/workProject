<?php
namespace App\DataFixtures;
use App\Entity\Activite;
use App\Entity\Objectif;
use App\Entity\Photo;
use App\Entity\Produit;
use App\Entity\Suivi;
use App\Entity\Veterinaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $connection = $manager->getConnection();
        $connection->exec("ALTER TABLE suivi AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE objectif AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE photo AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE activite AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE produit AUTO_INCREMENT = 1;");
        $connection->exec("ALTER TABLE veterinaire AUTO_INCREMENT = 1;");
        $djourMoins45jours = (new \Datetime())->sub(new \DateInterval('P45D'));
        $djourMoins30jours = (new \Datetime())->sub(new \DateInterval('P30D'));
        $djourMoins20jours = (new \Datetime())->sub(new \DateInterval('P20D'));
        $djourMoins15jours = (new \Datetime())->sub(new \DateInterval('P15D'));
        $djourMoins5jours = (new \Datetime())->sub(new \DateInterval('P5D'));

//region Les photos
        $photo1 = new Photo();
        $photo1->setUrlImage('http://www.veterinaire-evolia.com/photos/contenus/facade%20BD_215_fr.jpg');
        $photo1->setAltImage('Agencement cabinet vétérinaire Lasseau et Desguerre');
        $manager->persist($photo1);

        $photo2 = new Photo();
        $photo2->setUrlImage('http://www.justacote.com/photos_entreprises/cabinet-veterinaire-de-gaillard-gaillard-1268068749.jpg');
        $photo2->setAltImage('Cabinet véto Brahim et Radji');
        $manager->persist($photo2);

        $photo3 = new Photo();
        $photo3->setUrlImage('http://www.lessablesrivegauche.fr/media/01/02/1653378709.jpg');
        $photo3->setAltImage('Cabinet Saudubray');
        $manager->persist($photo3);

        $photo4 = new Photo();
        $photo4->setUrlImage('http://www.chamblyveterinaire.com/wp-content/uploads/2015/11/veterinaire-guide-soins-chien-1-370x272.jpg');
        $photo4->setAltImage('Chambly Veterinaires');
        $manager->persist($photo4);

        $photo5 = new Photo();
        $photo5->setUrlImage('http://cliniqueduvernet.com/wp-content/uploads/2013/01/logo2.png');
        $photo5->setAltImage('Clinique Duvernet');
        $manager->persist($photo5);
//endregion

//region Les activités
        $activite1 = new Activite();
        $activite1->setLibelle('Consultation');
        $manager->persist($activite1);

        $activite2 = new Activite();
        $activite2->setLibelle('Chirurgie générale');
        $manager->persist($activite2);

        $activite3 = new Activite();
        $activite3->setLibelle('Soins intensifs');
        $manager->persist($activite3);

        $activite4 = new Activite();
        $activite4->setLibelle('24h/24');
        $manager->persist($activite4);

        $activite5 = new Activite();
        $activite5->setLibelle('Service de garde');
        $manager->persist($activite5);

        $activite6 = new Activite();
        $activite6->setLibelle('Hospitalisation');
        $manager->persist($activite6);

        $activite7 = new Activite();
        $activite7->setLibelle('Imagerie médicale');
        $manager->persist($activite7);
//endregion

//region Les vétérinaires
        $veto1 = new Veterinaire();
        $veto1->setNom('Lasseau et Desguerre');
        $veto1->setAdresse('3 rue du 11 novembre');
        $veto1->setCodePostal('60340');
        $veto1->setVille('SAINT LEU D\'ESSERENT');
        $veto1->setTelephone('03.44.55.66.77');
        $veto1->setPhoto($photo1);
        $veto1->setDateCreation($djourMoins45jours);
        $veto1->addActivite($activite1);
        $veto1->addActivite($activite2);
        $veto1->addActivite($activite5);
        $veto1->addActivite($activite6);
        $manager->persist($veto1);

        $veto2 = new Veterinaire();
        $veto2->setNom('Saudubray Jérôme');
        $veto2->setAdresse('86 rue de la république');
        $veto2->setCodePostal('60100');
        $veto2->setVille('CREIL');
        $veto2->setTelephone('03.44.99.88.77');
        $veto2->setPhoto($photo3);
        $veto2->setDateCreation($djourMoins45jours);
        $veto2->addActivite($activite1);
        $veto2->addActivite($activite2);
        $veto2->addActivite($activite5);
        $veto2->addActivite($activite6);
        $manager->persist($veto2);

        $veto3 = new Veterinaire();
        $veto3->setNom('Brahim et Radji');
        $veto3->setAdresse('64 avenue Claude Péroche');
        $veto3->setCodePostal('60180');
        $veto3->setVille('NOGENT SUR OISE');
        $veto3->setTelephone('03.22.54.88.77');
        $veto3->setPhoto($photo2);
        $veto3->setDateCreation($djourMoins30jours);
        $veto3->addActivite($activite1);
        $veto3->addActivite($activite2);
        $veto3->addActivite($activite3);
        $veto3->addActivite($activite5);
        $veto3->addActivite($activite6);
        $veto3->addActivite($activite7);
        $manager->persist($veto3);

        $veto4 = new Veterinaire();
        $veto4->setNom('Clinique Duvernet');
        $veto4->setAdresse('30 rue des lilas');
        $veto4->setCodePostal('60500');
        $veto4->setVille('Chantilly');
        $veto4->setTelephone('03.44.55.99.88');
        $veto4->setPhoto($photo5);
        $veto4->setDateCreation($djourMoins20jours);
        $veto4->addActivite($activite1);
        $veto4->addActivite($activite2);
        $veto4->addActivite($activite3);
        $veto4->addActivite($activite4);
        $veto4->addActivite($activite5);
        $veto4->addActivite($activite6);
        $veto4->addActivite($activite7);
        $manager->persist($veto4);

        $veto5 = new Veterinaire();
        $veto5->setNom('Chambly Vétérinaires');
        $veto5->setAdresse('25 rue du pont');
        $veto5->setCodePostal('60230');
        $veto5->setVille('CHAMBLY');
        $veto5->setTelephone('01.02.01.02.01');
        $veto5->setPhoto($photo4);
        $veto5->setDateCreation($djourMoins15jours);
        $veto5->addActivite($activite1);
        $veto5->addActivite($activite2);
        $veto5->addActivite($activite5);
        $veto5->addActivite($activite6);
        $manager->persist($veto5);
//endregion

//region Les produits
        $produit1 = new Produit();
        $produit1->setNom('Feliway diffuseur 48ml');
        $produit1->setPrix(24.99);
        $manager->persist($produit1);

        $produit2 = new Produit();
        $produit2->setNom('Feliway recharge 48ml');
        $produit2->setPrix(19.99);
        $manager->persist($produit2);

        $produit3 = new Produit();
        $produit3->setNom('Feliway spray 60ml');
        $produit3->setPrix(22.99);
        $manager->persist($produit3);

        $produit4 = new Produit();
        $produit4->setNom('Feliway spray 20ml');
        $produit4->setPrix(11.99);
        $manager->persist($produit4);

        $produit5 = new Produit();
        $produit5->setNom('Dermoscent Pipettes Essential 6 spot-on chien <10kg');
        $produit5->setPrix(15.75);
        $manager->persist($produit5);

        $produit6 = new Produit();
        $produit6->setNom('Dermoscent Pipettes Essential 6 spot-on chien >20kg');
        $produit6->setPrix(20.95);
        $manager->persist($produit6);

        $produit7 = new Produit();
        $produit7->setNom('Dermoscent Pipettes Essential 6 spot-on chien 10-20kg');
        $produit7->setPrix(18.25);
        $manager->persist($produit7);

        $produit8 = new Produit();
        $produit8->setNom('Dermoscent Bio Balm soin coussinets plantaires et de la truffe');
        $produit8->setPrix(12.09);
        $manager->persist($produit8);

        $produit9 = new Produit();
        $produit9->setNom('Dermoscent Keravita pour chiens et chats');
        $produit9->setPrix(15.26);
        $manager->persist($produit9);

        $produit10 = new Produit();
        $produit10->setNom('Dermoscent Essential 6 Spot-on chat');
        $produit10->setPrix(16.39);
        $manager->persist($produit10);

        $produit11 = new Produit();
        $produit11->setNom('Collier Seresto GSB - Anti-puces et tiques pour chat');
        $produit11->setPrix(20.89);
        $manager->persist($produit11);

        $produit12 = new Produit();
        $produit12->setNom('Pipettes Fiprospot Spot-On - Anti-puces pour chat - 3 pipettes');
        $produit12->setPrix(7.14);
        $manager->persist($produit12);

        $produit13 = new Produit();
        $produit13->setNom('Pipettes Fiprospot Spot-On - Anti-puces pour chat - 6 pipettes');
        $produit13->setPrix(12.34);
        $manager->persist($produit13);

        $produit14 = new Produit();
        $produit14->setNom('Collier Seresto GSB - Anti-puces et tiques pour chien < 8kg');
        $produit14->setPrix(22.79);
        $manager->persist($produit14);

        $produit15 = new Produit();
        $produit15->setNom('Collier Seresto GSB - Anti-puces et tiques pour chien > 8kg');
        $produit15->setPrix(25.64);
        $manager->persist($produit15);

        $produit16 = new Produit();
        $produit16->setNom('Pipettes Fiprospot Spot-on - Anti-puces et tiques pour petit chien (2-10kg); - 6 pipettes');
        $produit16->setPrix(15.59);
        $manager->persist($produit16);

        $produit17 = new Produit();
        $produit17->setNom('Pipettes Fiprospot Spot-on - Anti-puces et tiques pour chien moyen (10-20kg) - 6 pipettes');
        $produit17->setPrix(17.54);
        $manager->persist($produit17);

        $produit18 = new Produit();
        $produit18->setNom('Pipettes Fiprospot Spot-on - Anti-puces et tiques pour grandchien (20-40kg) - 6 pipettes');
        $produit18->setPrix(20.79);
        $manager->persist($produit18);

        $produit19 = new Produit();
        $produit19->setNom('Pipettes Fiprospot Spot-on - Anti-puces et tiques pour chien géant (40-60kg) - 6 pipettes');
        $produit19->setPrix(22.74);
        $manager->persist($produit19);
//endregion

//region Les objectifs
        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit1);
        $objectif->setMontant(500);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit7);
        $objectif->setMontant(1000);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit3);
        $objectif->setMontant(500);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit8);
        $objectif->setMontant(800);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit12);
        $objectif->setMontant(500);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto1);
        $objectif->setProduit($produit9);
        $objectif->setMontant(1500);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto2);
        $objectif->setProduit($produit1);
        $objectif->setMontant(800);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto2);
        $objectif->setProduit($produit2);
        $objectif->setMontant(520);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto2);
        $objectif->setProduit($produit3);
        $objectif->setMontant(520);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto2);
        $objectif->setProduit($produit5);
        $objectif->setMontant(1000);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto2);
        $objectif->setProduit($produit12);
        $objectif->setMontant(800);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit1);
        $objectif->setMontant(700);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit2);
        $objectif->setMontant(1800);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit3);
        $objectif->setMontant(520);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit5);
        $objectif->setMontant(1000);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit7);
        $objectif->setMontant(300);
        $manager->persist($objectif);

        $objectif = new Objectif();
        $objectif->setVeterinaire($veto5);
        $objectif->setProduit($produit8);
        $objectif->setMontant(200);
        $manager->persist($objectif);
//endregion

//region Les suivis
        $suivi = new Suivi();
        $suivi->setNomContact('Mme Lasseau');
        $suivi->setCommentaire('Les ventes de colliers Seresto pour les chiens ont explosé.' . "\r\n". 'En rupture fiprospot chat.');
        $suivi->setDateAppel($djourMoins30jours);
        $suivi->setVeterinaire($veto1);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mme Lasseau');
        $suivi->setCommentaire('Prévoir un rendez-vous pour présenter la nouvelle gamme Dermoscent.');
        $suivi->setDateAppel($djourMoins20jours);
        $suivi->setVeterinaire($veto1);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mme Desguerres');
        $suivi->setCommentaire('Stocks en baisse sur l\'\'ensemble des produits. Prévoir une visite très rapidement 
pour présentation nouvelles gamme avant réapprovisionnement');
        $suivi->setDateAppel($djourMoins5jours);
        $suivi->setVeterinaire($veto1);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mme Lasseau');
        $suivi->setCommentaire('Réception des nouveaux produits effectuée. A rappeler dans 2 semaines.');
        $suivi->setDateAppel(new \Datetime());
        $suivi->setVeterinaire($veto1);
        $manager->persist($suivi);

//*********************************************

        $suivi = new Suivi();
        $suivi->setNomContact('Mr Dupont Jean-Pierre');
        $suivi->setCommentaire('Bonnes ventes sur le produit Feliway diffuseur.' . "\r\n". 'Souhaite un complément d\'\'infos sur la gamme Dermoscent.');
        $suivi->setDateAppel($djourMoins45jours);
        $suivi->setVeterinaire($veto3);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mr Dupont Jean-Pierre');
        $suivi->setCommentaire('La gamme dermoscent marche plutôt bien.');
        $suivi->setDateAppel($djourMoins20jours);
        $suivi->setVeterinaire($veto3);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mr Dupont Jean-Pierre');
        $suivi->setCommentaire('Stocks en baisse sur l\'\'ensemble des produits. Prévoir une visite très rapidement 
pour présentation nouvelles gammes avant réapprovisionnement');
        $suivi->setDateAppel(new \Datetime());
        $suivi->setVeterinaire($veto3);
        $manager->persist($suivi);

//*********************************************

        $suivi = new Suivi();
        $suivi->setNomContact('Mlle Pierval Magalie');
        $suivi->setCommentaire('Première prise de contact. Bonne approche.' . "\r\n". 'Rendez-vous à prévoir');
        $suivi->setDateAppel($djourMoins15jours);
        $suivi->setVeterinaire($veto4);
        $manager->persist($suivi);

        $suivi = new Suivi();
        $suivi->setNomContact('Mlle Pierval Magalie');
        $suivi->setCommentaire('Les produits de la gamme Seresto marchent plutôt bien. Il faudrait étendre la gamme aux chats');
        $suivi->setDateAppel(new \Datetime());
        $suivi->setVeterinaire($veto4);
        $manager->persist($suivi);

//*********************************************

        $suivi = new Suivi();
        $suivi->setNomContact('Mme Donicar Liliane');
        $suivi->setCommentaire('Première prise de contact. Bonne approche.' . "\r\n". 'Rendez-vous à prévoir');
        $suivi->setDateAppel($djourMoins5jours);
        $suivi->setVeterinaire($veto5);
        $manager->persist($suivi);
//endregion

        $manager->flush();
    }
}
