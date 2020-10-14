<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Figures;
use App\Entity\Client;
use App\Entity\Image;
use App\Entity\Video;
class AppFixtures extends Fixture
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder, $upload_directory)
	{
		$this->upload_directory = $upload_directory;
	    $this->encoder = $encoder;
	}
    
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setFirstName('Admin')
        	->setLastName('Admin')
        	->setEmail('admin@admin.fr')
        	->setPassword($this->encoder->encodePassword($client, 'admin1234'))
       	;
       	$manager->persist($client);
        $manager->flush();

        $data_post = [
            ['360', 'trois six pour un tour complet.', 0, ["https://www.imperiumsnow.com/upload/friedl-fs-360-0.jpg", 'image1.jpg'], ['https://www.youtube.com/embed/JJy39dO_PPE']],

            ['truck driver', 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)', 0, ["https://ucarecdn.com/b6aa154c-3a98-4dcd-b2f9-282467820b6a/-/sharp/3/-/format/jpeg/-/progressive/yes/-/quality/normal/-/scale_crop/298x298/center/", 'image2.jpg'], ['https://www.youtube.com/embed/6tgjY8baFT0']],

            ['seat belt', 'saisie du carre frontside à l\'arrière avec la main avant', 0, ['https://upload.wikimedia.org/wikipedia/commons/7/70/Picswiss_VD-44-23.jpg', 'image3.jpg'], ['https://www.youtube.com/embed/4vGEOYNGi_c']],

            ['japan air', 'saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.', 0, ['https://www.arts-majeurs.com/uploads/images/tricks/21.jpg', 'image4.jpg'], ['https://www.youtube.com/embed/jH76540wSqU']],

            ['stalefish ', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière', 0, ['https://i1.wp.com/www.snowboarder.com/wp-content/uploads/2015/06/stale_johnny_oconnor_back_fenelon.jpg?resize=1200%2C675', 'image5.jpg'], ['https://www.youtube.com/embed/f9FjhCt_w2U']],

            ['bluntslide', 'Le bluntslide est un trick de skateboard. Il fait partie de la catégorie des slides et s\'effectue par conséquent sur un rail, un curb, ou un autre élément s\'y apparentant.', 1, ['https://cdn.shopify.com/s/files/1/0230/2239/files/2_d9cffdae-d6f6-4b1f-8a9b-1980600a86dd_large.jpg', 'image6.jpg'], ['https://www.youtube.com/embed/Nkotow1RyaQ']],

            ['half-pipe', 'La rampe, ou half-pipe (ou halfpipe), est un des types de modules de skatepark que l\'on peut trouver dans les skateparks. C\'est également le nom d\'une discipline du skateboard, du roller et du BMX. On l\'appelle également la « big », la « vert » (venant de « verticale »), ou encore la « courbe ». C\'est également une épreuve olympique en surf des neiges et en ski.', 1, ['https://i.ytimg.com/vi/FKkc66pUrf8/maxresdefault.jpg', 'image7.jpg'], ['https://www.youtube.com/embed/Kqz0z8opjfM']],

            ['boardercross', 'Le boardercross, bladercross ou snowboardcross est un parcours d\'obstacle chronométré sur piste comportant des bosses, des portes et des virages relevés.', 1, ['https://lh3.googleusercontent.com/proxy/QPkqs94vgdkpMmLabHx8PF-AK8jVW8klE7Gdfx-NSyAEH7BFXoZtGtEYXhW1TMphQQTAfOvnvAGUzVc6es2YlNyN9VvoF92_uHUfGiB06JbVdSuM0qQM4GN5h4_MrbV5pMZTG93k3KGtA5_16CuuClucW2g3w42FP13_LXtV7_2L', 'image8.jpg'], ['https://www.youtube.com/embed/lJhaUKC-0wg']],

            ['1080', 'ou big foot pour trois tours', 1, ['https://cdn.futura-sciences.com/buildsv6/images/wide1920/c/0/b/c0b199d73b_120515_lexique-snowboard.jpg', 'image9.jpg'], ['https://www.youtube.com/embed/camHB0Rj4gA']],

            ['nose grab', 'saisie de la partie avant de la planche, avec la main avant', 1, ['https://twistedsifter.files.wordpress.com/2011/01/nose-grab-snowboarding.jpg?w=1980&h=1080', 'image10.jpg'], ['https://www.youtube.com/embed/M-W7Pmo-YMY']]
        ];

        foreach ($data_post as $value) {
            $figure = new Figures();

            $figure->setFigure($value[0])
                ->setDescription($value[1])
                ->setGroupe($value[2])
            ;
            $image = $value[3];
            $images = new Image();
            file_put_contents($this->upload_directory . '/' . $image[1], file_get_contents($image[0]));
            $images->setImage($image[1]);
            $figure->addImage($images);


            foreach ($value[4] as $video) {
                $videos = new Video();
                $videos->setVideo($video);
                $figure->addVideo($videos);
            }

            $figure->setIdClient($client);
            $figure->updateTimestamps();
            $manager->persist($figure);
            $manager->flush();
        }
    }
}
