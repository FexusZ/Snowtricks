<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Figures;
use App\Entity\Client;
use App\Entity\Image;
use App\Entity\Video;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var
     */
    private $upload_directory;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param $upload_directory
     */
    public function __construct(UserPasswordEncoderInterface $encoder, $upload_directory)
	{
		$this->upload_directory = $upload_directory;
	    $this->encoder = $encoder;
	}

    /**
     * @param ObjectManager $manager
     */
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
            ['360', 'trois six pour un tour complet.', 0, ['https://www.youtube.com/embed/JJy39dO_PPE']],

            ['truck driver', 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)', 0, ['https://www.youtube.com/embed/6tgjY8baFT0']],

            ['seat belt', 'saisie du carre frontside à l\'arrière avec la main avant', 0, ['https://www.youtube.com/embed/4vGEOYNGi_c']],

            ['japan air', 'saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.', 0, ['https://www.youtube.com/embed/jH76540wSqU']],

            ['stalefish ', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière', 0, ['https://www.youtube.com/embed/f9FjhCt_w2U']],

            ['bluntslide', 'Le bluntslide est un trick de skateboard. Il fait partie de la catégorie des slides et s\'effectue par conséquent sur un rail, un curb, ou un autre élément s\'y apparentant.', 1, ['https://www.youtube.com/embed/Nkotow1RyaQ']],

            ['half-pipe', 'La rampe, ou half-pipe (ou halfpipe), est un des types de modules de skatepark que l\'on peut trouver dans les skateparks. C\'est également le nom d\'une discipline du skateboard, du roller et du BMX. On l\'appelle également la « big », la « vert » (venant de « verticale »), ou encore la « courbe ». C\'est également une épreuve olympique en surf des neiges et en ski.', 1, ['https://www.youtube.com/embed/Kqz0z8opjfM']],

            ['boardercross', 'Le boardercross, bladercross ou snowboardcross est un parcours d\'obstacle chronométré sur piste comportant des bosses, des portes et des virages relevés.', 1, ['https://www.youtube.com/embed/lJhaUKC-0wg']],

            ['1080', 'ou big foot pour trois tours', 1, ['https://www.youtube.com/embed/camHB0Rj4gA']],

            ['nose grab', 'saisie de la partie avant de la planche, avec la main avant', 1, ['https://www.youtube.com/embed/M-W7Pmo-YMY']]
        ];

        $i=1;
        foreach ($data_post as $value) {
            $figure = new Figures();

            $figure->setFigure($value[0])
                ->setDescription($value[1])
                ->setGroupe($value[2])
            ;
            $images = new Image();
            $file_name = md5(uniqid()) . '.jpg';
            file_put_contents($this->upload_directory . '/' . $file_name, file_get_contents($this->upload_directory . '/fixtures/image' . $i . '.jpg'));
            $images->setImage($file_name);
            $figure->addImage($images);


            foreach ($value[3] as $video) {
                $videos = new Video();
                $videos->setVideo($video);
                $figure->addVideo($videos);
            }

            $figure->setIdClient($client);
            $figure->updateTimestamps();
            $manager->persist($figure);
            $manager->flush();
            $i++;
        }
    }
}
