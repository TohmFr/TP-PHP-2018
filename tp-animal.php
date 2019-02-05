<?php

abstract class Animal implements ICrier{
    protected $nom;


    public $poil;

    public function caresser(){
        return "{$this->nom} est content";
    }

}

class Chien extends Animal{
    use Crier{
        crier as aboyer;
    }
    public function __construct(){
        $this->bruit = 'Ahouahou';
        $this->nom = 'Chien';
    }
}


class Chat extends Animal{
    use Crier{
        crier as miauler;
    }
    public function __construct(){
        $this->bruit = 'Miaou';
        $this->nom = 'Chat';
    }

}


class Cheval extends Animal {
    use Crier{
        crier as hennir;
    }
    public function __construct(){
        $this->bruit = 'Hiiii';
        $this->nom = 'Cheval';
    }
}

trait Crier {
    protected $bruit;

    public function Crier(){
        return $this->bruit;
    }
}
interface ICrier{

    function Crier();
}

$chien   = new Chien();
$chat    = new Chat();
$cheval  = new Cheval();

$html = <<<HTML
<h1>Chien</h1>
     {$chien->caresser()}<br />
     {$chien->aboyer()}
<h1>Chat</h1>
    {$chat->caresser()}<br />
    {$chat->miauler()}
<h1>Cheval</h1>
    {$cheval->caresser()}<br />
    {$cheval->hennir()}
HTML;


echo $html;