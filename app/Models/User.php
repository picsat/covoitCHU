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

    /*
    public function __construct($nom=null, $prenom=null, $email=null) // Constructeur demandant les paramètres
    {
        $this->setNom($this->nom); // Initialisation du Nom.
        $this->setPrenom($prenom); // Initialisation du Prenom.
        $this->setEmail($email); // Initialisation du mail.
    }
    */

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
        return "$this->prenom $this->nom";
    }

    public function getColor()
    {
        return "$this->color";
    }

    public function getEmailVariables()
    {
        return [
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
        ];
    }

    public function macaron() : string
    {
        $nom_initiale = ''; // déclare le recipient
        $n_mot = explode(" ",$this->prenom.' '.$this->nom);
        foreach($n_mot as $lettre)
        {
            $nom_initiale .= $lettre{0}.'.';
        }
        return strtoupper(substr($nom_initiale, 0, -1));
    }


    public function events()
    {
        //return $this->hasMany('App\Models\Event', 'id_user');

        return $this->hasMany('App\Models\Event', 'id_user')
        ->join('type_event', 'events.id_type',  '=', 'type_event.id')
        ->select('events.*', 'type_event.titre as titre_event');
    }


}
