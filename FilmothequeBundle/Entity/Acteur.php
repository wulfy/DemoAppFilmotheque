<?php
namespace DemoApp\FilmothequeBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Acteur
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(
     *      min = "3",minMessage = "Le nom de famille doit avoir au moins {{ limit }} caractères")
     */    
    private $nom;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(
     *      min = "3",minMessage = "erreur.nom.minlength")
     */    
    private $prenom;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */    
    private $dateNaissance;

    /**
     * @ORM\Column(type="string",length=1)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"M", "F"})
     */    
    private $sexe;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Acteur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Acteur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Acteur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Acteur
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }
	
	/**
     * Get Lastname and name
     *
     * @return string 
     */
	public function getPrenomNom()
	{
		return $this->prenom.' '.$this->nom;
	}
}
