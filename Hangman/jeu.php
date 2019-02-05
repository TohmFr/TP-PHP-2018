<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 10/10/2018
 * Time: 10:51
 */

$vie = empty($_POST["vie"]) ? 0:$_POST["vie"];

$varjs = <<<JS
    let vie="$vie";
JS;

?>
<html>
<head>
    <title>Jeu </title>
    <style>
        .main{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        #lettres, #motPartiel,#vieCont{
            display: flex;
            width : 420px;
            flex-wrap: wrap;
            font-size: 25px;
            font-variant: small-caps;
        }
        #lettres input{
            transition: all 1s ease-in;
        }

        #lettres input[disabled]{
            color: red;
        }
        #motPartiel, #vieCont{
            box-sizing: content-box;
            width : auto;
        }
        #rejouer{
            display: inline-block;
            text-decoration: none;
            font-size: 25px;
            height: 50px;
            background: aliceblue;
            text-align: center;
            vertical-align: middle;
            line-height: 50px;
            padding: 0 25px;
            border: 1px solid darkgrey;
            margin: 25px;
        }
        #vieCont div{
            background-image:   url("Heart.svg");
            background-size:    contain;
            background-repeat:  no-repeat;
            background-position: center center;
            transition: all 1s ease-in;
            transform-origin: center center;
        }
        #lettres input, #motPartiel div,  #vieCont div{
            width : 50px;
            height: 50px;
            margin: 5px;
            display: block;
            font-variant: small-caps;
        }
        #motPartiel div{
            transition: all 1s ease-in;
        }
        #motPartiel div.trouve{
            background: gold;
        }
        #motPartiel div{
            background:aliceblue;
            text-align: center;
            vertical-align: middle;
            line-height: 50px;
        }

        svg {
            margin: 0 auto;
            border: 1px solid black;
        }
        svg g, svg line {
            transition: all 1s ease-out;
        }

        line {
            stroke: black;
            stroke-width: 4;
        }
        #door1{

            transition: all 2s ease-in;
             transform-origin: 150px 250px;
        }
        #door2{

            transition: all 2s ease-in;
            transform-origin: 250px 250px;
        }
        .anim2{
            transition-delay: 0s;
            transition: all 3s cubic-bezier(0,2.27,1,.51);;
        }
        #rope{
            transform-origin: 200px 20px;
        }
        .hide{
            opacity: 0;
        }

        #motsTrouves .trouve{
              color: green;
        }
        #motsTrouves .pastrouve{
            color: red;
        }
        .dn{
            display: none;
        }
    </style>

    <script>
        <?php echo $varjs ?>

        document.addEventListener("DOMContentLoaded", function(event) {
            let hangMan = ['potence1','potence2','rope','head','realBody','armL','armR','legL','legR'].reverse();

            let domMotPartiel = document.getElementById('motPartiel');
            let domVie = document.getElementById('vie');
            let domFini =document.getElementById('fini');
            let lettrescont = document.getElementById('lettres');
            let domVieCont= document.getElementById('vieCont');
            let arrVie =[];
            let domSvg = document.getElementById("svg");
            function affichage(data){
                affichageMotPartiel(data.motPartiel);

                affichageVie(data.vie);

                domVie.textContent = data.vie;

                if(data.fini){
                    if(data.gagne){
                        domFini.appendChild(document.createTextNode("Gagner !!! "));
                    }
                    else{
                        domFini.appendChild(document.createTextNode("Perdu !!! "));
                    }
                    let a = document.createElement('a');
                    a.setAttribute("href","https://fr.wiktionary.org/wiki/" + data.mot);
                    a.setAttribute("target","_blank");
                    a.textContent=data.mot;
                    domFini.appendChild(a);
                    lettrescont.style.display="none";

                }
                affichageHangman(data.vie);

            }


            let motPartielLettres =[];
            function affichageMotPartiel(mot){
                let div = null;
                if(motPartielLettres.length===0) {
                    while (domMotPartiel.firstChild) {
                        domMotPartiel.removeChild(domMotPartiel.firstChild);
                    }
                    for (let c of Array.from(mot)) {
                        div = document.createElement("div");
                        div.textContent = c;
                        div._lettre = c;
                        domMotPartiel.appendChild(div);
                        motPartielLettres.push(div);
                    }
                }
                else {
                    Array.from(mot).forEach(function (element, index) {
                        div = motPartielLettres[index];
                        if(div._lettre !== element){
                            div.textContent = element;
                            div._lettre = element;
                            div.classList.add("trouve");
                            ((d) => {
                                setTimeout(()=> {
                                    d.classList.remove("trouve");
                                },1000);
                            })(div);

                        }
                    });
                }
            }

            function affichageVie(vie) {
                for(let i=9;i>(vie-1);i--){
                    let domVie = arrVie[i];
                    domVie.style.transform = "scale(0, 0)";
                }
            }

            function affichageHangman(vie){
                for(let i=0;i<hangMan.length;i++){
                    document.getElementById(hangMan[i]).style.opacity= (i<(vie-1))?"0":"1";
                }

                if(vie===0){

                    let door1 = document.getElementById("door1");
                    let door2 = document.getElementById("door2");
                    let rope  = document.getElementById("rope");
                    let body  = document.getElementById("body");
                    door1.style.transform=("rotate(90deg)");
                    door2.style.transform=("rotate(-90deg)");
                    rope.classList.add("anim2");
                    body.classList.add("anim2");
                    setTimeout(()=> {
                        rope.style.transform = "scale(1," + (1.0 + (100.0/40.0)) + " )";
                        body.style.transform = "translate(0,100px)";
                        document.getElementById("rEyes").style.opacity="0";
                        document.getElementById("xEyes").style.opacity="1";

                    },2000);
                }
                domSvg.style.display="";


            }

            function retryFetch(){
                fetch("ajaxJeu.php", {
                        method: "POST"
                    })
                    .then(function(res){
                        return res.json();
                    })
                    .then(function(json){
                        affichage(json);
                    })
            }
            function lettreClick(e){
                let fd = new URLSearchParams();
                let value = this.value;
                if(e.disabled)return;

                for (let input of lettrescont.getElementsByTagName("input")) {
                    input.disabled = true;
                };

                fd.append("lettre",value);

                fetch("ajaxJeu.php",
                    {
                        method: "POST",
                        body:  fd
                    })
                    .then(function(res){
                        console.log(res);
                        return res.json();
                    })
                    .then(function(json){
                        console.log(json);
                        affichage(json);
                    })
                    .catch(function(res){
                        console.log(res);
                        retryFetch();
                    })
                    .finally(()=>{
                        for (let input of lettrescont.getElementsByTagName("input")) {
                            input.disabled = false;
                        };
                    });
                this.style.display="none";
            }
            function init(){

                let fd = new URLSearchParams();
                fd.append("vie",vie);
                fd.append("init",'1');
                fetch("ajaxJeu.php",
                    {
                       /* headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },*/
                        method: "POST",
                        body:  fd
                    })
                    .then(function(res){
                        return res.json();
                    })
                    .then(function(json){
                        affichage(json);
                    })
                    .catch(function(res){ console.log(res) });

                let aCharCode ='a'.charCodeAt(0);
                let tabKey ={};

                for(let i=0;i<26;i++){
                    let lettre = String.fromCharCode(aCharCode + i);
                    let input = document.createElement("input");
                    input.setAttribute('type','button');
                    input.setAttribute('value',lettre);
                    input.classList.add('lettre');
                    input.textContent = lettre;
                    lettrescont.appendChild(input);
                    input.addEventListener("click",lettreClick)
                    tabKey[lettre] = input;

                }
                ((tabKey)=> {
                    document.addEventListener("keydown",function(event){
                        const keyName = event.key;
                        const input = tabKey[keyName];
                        if ((input) && (input.parentNode)){
                            const event = new Event('click');
                            input.dispatchEvent(event);
                            tabKey[keyName]=null;
                        }
                    });
                })(tabKey);
            };

            function listMotTrouves(){
                let fd = new FormData();
                fd.append("action", "listMotsTrouves");
                fetch("ajaxJeu.php",
                    {
                        method: "POST",
                        body:  fd
                    })
                    .then(function(res){
                        return res.json();
                    })
                    .then(function(json){
                        let ul = document.getElementById("motsTrouves");

                        for(mot of json)
                        {
                            let li = document.createElement("li");
                            li.textContent = mot.mot;
                            li.classList.add(mot.trouve===0?"pasTrouve":"trouve");
                            ul.append(li);
                        }


                    })
                    .catch(function(res){ console.log(res) });

            }

            function initCoeur(){

                for(let i =1; i<=10; i++){
                    let div = document.createElement("div");
                    domVieCont.appendChild(div);
                    arrVie.push(div);
                }
            }
            initCoeur();
            init();
            listMotTrouves();
        });
    </script>
</head>
<body>
<div class="main">
    <div id="motPartiel"></div>

    <p class="dn">
        vie:<span id="vie"></span>
    </p>
    <div id="vieCont">

    </div>
    <p id="fini">

    </p>
    <div>
        <svg height="400" width="400" id="svg" style="display: none">
            <g id="body">
                <g id="head">
                    <circle cx="200" cy="80" r="20" stroke="black" stroke-width="4" fill="white"/>
                    <g id="rEyes">
                        <circle cx="193" cy="80" r="4"/>
                        <circle cx="207" cy="80" r="4"/>
                    </g>
                    <g id="xEyes" class="hide">
                        <line x1="190" y1="78" x2="196" y2="84"/>
                        <line x1="204" y1="78" x2="210" y2="84"/>
                        <line x1="190" y1="84" x2="196" y2="78"/>
                        <line x1="204" y1="84" x2="210" y2="78"/>
                    </g>
                </g>
                <line x1="200" y1="100" x2="200" y2="150" id="realBody"/>
                <line id="armL" x1="200" y1="120" x2="170" y2="140" />
                <line id="armR" x1="200" y1="120" x2="230" y2="140" />
                <line id="legL" x1="200" y1="150" x2="180" y2="190" />
                <line id="legR" x1="200" y1="150" x2="220" y2="190" />
            </g>
            <line x1="10" y1="250" x2="150" y2="250" />
            <line id="door1" x1="150" y1="250" x2="200" y2="250" />
            <line  id="door2" x1="200" y1="250" x2="250" y2="250" />
            <line x1="250" y1="250" x2="390" y2="250" id=""/>
            <line x1="100" y1="250" x2="100" y2="20"  id="potence1"/>
            <line x1="100" y1="20" x2="200" y2="20" id="potence2"/>
            <line  x1="200" y1="20" x2="200" y2="60" id="rope"/>
        </svg>
    </div>
    <div id="lettres" >

    </div>


    <!--<a id="rejouer" href="/edsa-TP/Hangman/">Rejouer</a>-->
    <a id="rejouer" href="/edsa-TP/Hangman/jeu.php?init=1">Rejouer</a>
    <ul id="motsTrouves">

    </ul>
</div>
</body>
</html>
