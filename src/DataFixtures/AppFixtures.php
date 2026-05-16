<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Meuble;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    private function slug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = str_replace(['é','è','ê','ë'], 'e', $text);
        $text = str_replace(['à','â','ä'], 'a', $text);
        $text = str_replace(['ù','û','ü'], 'u', $text);
        $text = str_replace(['î','ï'], 'i', $text);
        $text = str_replace(['ô','ö'], 'o', $text);
        $text = str_replace(['ç'], 'c', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            'Séjour' => [
                'description' => 'Canapés, tables basses et meubles TV pour votre salon.',
                'meubles' => [
                    ['Canapé 3 places', 'Canapé confortable en tissu gris anthracite, idéal pour 3 personnes.', 599.00, 10, 'canape-3-places.jpg'],
                    ['Table basse en bois', 'Table basse en chêne massif avec plateau en verre trempé.', 189.00, 15, 'table-basse.jpg'],
                    ['Meuble TV 150cm', 'Meuble TV avec 2 portes coulissantes et rangement intégré.', 249.00, 8, 'meuble-tv.jpg'],
                    ["Canapé d'angle", "Grand canapé d'angle en cuir noir, très confortable et moderne.", 899.00, 5, 'canape-angle.jpg'],
                    ['Étagère murale salon', 'Étagère murale en bois flotté, 3 niveaux, style scandinave.', 79.00, 20, 'etagere-murale.jpg'],
                ],
            ],
            'Chambre' => [
                'description' => 'Lits, armoires et tables de chevet pour votre chambre.',
                'meubles' => [
                    ['Lit 160x200', 'Lit double avec tête de lit rembourrée en velours bleu nuit.', 449.00, 7, 'lit-160.jpg'],
                    ['Armoire 3 portes', 'Grande armoire avec miroir central et rangements multiples.', 599.00, 6, 'armoire-3-portes.jpg'],
                    ['Table de chevet', 'Table de chevet avec tiroir et niche ouverte, en bois naturel.', 89.00, 25, 'table-chevet.jpg'],
                    ['Lit 90x200', 'Lit simple en bois de pin massif, idéal pour chambre enfant.', 229.00, 12, 'lit-90.jpg'],
                    ['Commode 5 tiroirs', 'Commode blanche avec 5 tiroirs spacieux et poignées dorées.', 279.00, 9, 'commode.jpg'],
                ],
            ],
            'Bureau' => [
                'description' => 'Bureaux, chaises ergonomiques et étagères pour travailler.',
                'meubles' => [
                    ['Bureau en L', 'Bureau en L avec retour et rangement latéral, finition chêne.', 349.00, 8, 'bureau-l.jpg'],
                    ['Chaise ergonomique', 'Chaise de bureau réglable avec soutien lombaire et accoudoirs.', 199.00, 15, 'chaise-ergonomique.jpg'],
                    ['Bibliothèque 5 niveaux', 'Grande bibliothèque en bois avec 5 étagères ajustables.', 159.00, 10, 'bibliotheque.jpg'],
                    ['Bureau compact', 'Bureau compact idéal pour petits espaces, tiroir intégré.', 149.00, 18, 'bureau-compact.jpg'],
                    ['Fauteuil de direction', 'Fauteuil de direction en cuir véritable avec accoudoirs réglables.', 329.00, 6, 'fauteuil-direction.jpg'],
                ],
            ],
            'Cuisine' => [
                'description' => 'Tables de repas, chaises et éléments de cuisine.',
                'meubles' => [
                    ['Table de repas 6 personnes', 'Table extensible en chêne pour 6 à 8 personnes.', 499.00, 7, 'table-repas.jpg'],
                    ['Chaise de cuisine lot 4', 'Chaise en bois avec assise rembourrée, vendue par lot de 4.', 239.00, 20, 'chaise-cuisine.jpg'],
                    ['Îlot de cuisine', 'Îlot central avec plan de travail en marbre et rangements.', 799.00, 4, 'ilot-cuisine.jpg'],
                    ['Tabouret de bar lot 2', 'Tabouret de bar réglable en hauteur, vendu par lot de 2.', 149.00, 14, 'tabouret-bar.jpg'],
                    ['Buffet de cuisine', 'Buffet 2 portes avec tiroirs et plan de travail en bois.', 359.00, 9, 'buffet-cuisine.jpg'],
                ],
            ],
        ];

        $slugCount = [];

        foreach ($data as $libelle => $info) {
            $categorie = new Categorie();
            $categorie->setLibelle($libelle);
            $categorie->setSlug($this->slug($libelle));
            $categorie->setDescription($info['description']);
            $manager->persist($categorie);

            foreach ($info['meubles'] as [$nom, $description, $prix, $stock, $image]) {
                $meuble = new Meuble();
                $meuble->setNom($nom);
                $baseSlug = $this->slug($nom);
                $slugCount[$baseSlug] = ($slugCount[$baseSlug] ?? 0) + 1;
                $meuble->setSlug($slugCount[$baseSlug] > 1 ? $baseSlug . '-' . $slugCount[$baseSlug] : $baseSlug);
                $meuble->setDescription($description);
                $meuble->setPrix($prix);
                $meuble->setStock($stock);
                $meuble->setImage($image);
                $meuble->setCategorie($categorie);
                $manager->persist($meuble);
            }
        }

        $user = new User();
        $user->setNom('Saidi');
        $user->setPrenom('Mohamed Rayen');
        $user->setEmail('rayensaidi@gmail.com');
        $user->setTelephone('27256925');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'rayen1234'));
        $manager->persist($user);

        $admin = new User();
        $admin->setNom('Ksouri');
        $admin->setPrenom('Radhouen');
        $admin->setEmail('radhouen@gmail.com');
        $admin->setTelephone('94626761');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $manager->flush();
    }
}