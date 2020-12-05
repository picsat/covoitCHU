<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/*class User extends Model{
    public $timestamps = false;
    protected $table = 'users';
    protected $fillable = ['nom','prenom','ville','email','password'];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
*/
class User extends Model
{
    protected $table = 'users';

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    protected $prenom;
    protected $nom;
    protected $email;

    protected $fillable = [
        'email',
        'nom',
        'prenom',
        'ville',
        'password',
    ];

     /*public function __construct($nom, $prenom, $email) // Constructeur demandant les paramètres
    {
        $this->setNom($nom); // Initialisation du Nom.
        $this->setPrenom($prenom); // Initialisation du Prenom.
        $this->setEmail($email); // Initialisation du mail.
    }*/

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function setPrenom($prenom)
    {
        $this->prenom = trim($prenom);
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setNom($nom)
    {
        $this->nom = trim($nom);
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFullNom()
    {
        return "$this->prenom +++ $this->nom +++ $this->email";
    }

    public function getEmailVariables()
    {
        return [
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
        ];
    }
}
