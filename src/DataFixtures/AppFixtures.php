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
                    ['Canapé 3 places', 'Canapé confortable en tissu gris anthracite, idéal pour 3 personnes.', 599.00, 10, 'https://images.unsplash.com/photo-1549187774-b4e9b0445b41?w=1200'],
                    ['Table basse en bois', 'Table basse en chêne massif avec plateau en verre trempé.', 189.00, 15, 'https://images.unsplash.com/photo-1532372320978-9b0a9f4f4f87?w=1200'],
                    ['Meuble TV 150cm', 'Meuble TV avec 2 portes coulissantes et rangement intégré.', 249.00, 8, 'https://images.unsplash.com/photo-1582582621959-48d27397dc69?w=1200'],
                    ["Canapé d'angle", "Grand canapé d'angle en cuir noir, très confortable et moderne.", 899.00, 5, 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=1200'],
                    ['Étagère murale salon', 'Étagère murale en bois flotté, 3 niveaux, style scandinave.', 79.00, 20, 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1200'],
                ],
            ],
            'Chambre' => [
                'description' => 'Lits, armoires et tables de chevet pour votre chambre.',
                'meubles' => [
                    ['Lit 160x200', 'Lit double avec tête de lit rembourrée en velours bleu nuit.', 449.00, 7, 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=1200'],
                    ['Armoire 3 portes', 'Grande armoire avec miroir central et rangements multiples.', 599.00, 6, 'https://images.unsplash.com/photo-1556020685-ae41abfc9365?w=1200'],
                    ['Table de chevet', 'Table de chevet avec tiroir et niche ouverte, en bois naturel.', 89.00, 25, 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=1200'],
                    ['Lit 90x200', 'Lit simple en bois de pin massif, idéal pour chambre enfant.', 229.00, 12, 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=1200'],
                    ['Commode 5 tiroirs', 'Commode blanche avec 5 tiroirs spacieux et poignées dorées.', 279.00, 9, 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1200'],
                ],
            ],
            'Bureau' => [
                'description' => 'Bureaux, chaises ergonomiques et étagères pour travailler.',
                'meubles' => [
                    ['Bureau en L', 'Bureau en L avec retour et rangement latéral, finition chêne.', 349.00, 8, 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200'],
                    ['Chaise ergonomique', 'Chaise de bureau réglable avec soutien lombaire et accoudoirs.', 199.00, 15, 'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?w=1200'],
                    ['Bibliothèque 5 niveaux', 'Grande bibliothèque en bois avec 5 étagères ajustables.', 159.00, 10, 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200'],
                    ['Bureau compact', 'Bureau compact idéal pour petits espaces, tiroir intégré.', 149.00, 18, 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=1200'],
                    ['Fauteuil de direction', 'Fauteuil de direction en cuir véritable avec accoudoirs réglables.', 329.00, 6, 'https://images.unsplash.com/photo-1579656592043-a20e25a4aa4b?w=1200'],
                ],
            ],
            'Cuisine' => [
                'description' => 'Tables de repas, chaises et éléments de cuisine.',
                'meubles' => [
                    ['Table de repas 6 personnes', 'Table extensible en chêne pour 6 à 8 personnes.', 499.00, 7, 'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=1200'],
                    ['Chaise de cuisine lot 4', 'Chaise en bois avec assise rembourrée, vendue par lot de 4.', 239.00, 20, 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=1200'],
                    ['Îlot de cuisine', 'Îlot central avec plan de travail en marbre et rangements.', 799.00, 4, 'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=1200'],
                    ['Tabouret de bar lot 2', 'Tabouret de bar réglable en hauteur, vendu par lot de 2.', 149.00, 14, 'https://images.unsplash.com/photo-1517705008128-361805f42e86?w=1200'],
                    ['Buffet de cuisine', 'Buffet 2 portes avec tiroirs et plan de travail en bois.', 359.00, 9, 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1200'],
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

        // ── Client normal ──────────────────────────────────────────────
        $user = new User();
        $user->setNom('Saidi');
        $user->setPrenom('Mohamed Rayen');
        $user->setEmail('rayensaidi@gmail.com');
        $user->setTelephone('27256925');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'rayen1234'));
        $manager->persist($user);

        // ── Administrateur ─────────────────────────────────────────────
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