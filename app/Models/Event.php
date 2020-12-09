<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Event extends Model
{
    protected $table = 'events';

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    protected $date;
    protected $voiture;

    protected $fillable = [
        'date',
        'voiture',
        'id_type',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');

    }

    public function getAllEventType()
    {
        //return $type = $this->join('type_event as t', 'id_type','t.id','=','id_type')->select('id_type', 't.titre as titre_event')->get();
        $type =  DB::table('type_event')->select('*')->get();
        return $this->translatedEventType = $type;
        //return $type = DB::table('type_event')->select('titre as titre_event')->where('id','=', 'id_type')->get();

    }

    public function getEventType()
    {
        //return $type = $this->join('type_event as t', 'id_type','t.id','=','id_type')->select('id_type', 't.titre as titre_event')->get();
        $type =  DB::table('type_event')->select('id', 'titre as titre_event', 'descriptif')->where('id','=',$this->id_type)->get();
        return $this->translatedEventType = $type;
        //return $type = DB::table('type_event')->select('titre as titre_event')->where('id','=', 'id_type')->get();

    }
}
