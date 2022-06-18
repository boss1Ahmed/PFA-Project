function checkString(S){
    const re = /^([a-zA-Z]+[0-9]*.([a-zA-Z]|[0-9])*)*$/;
    return re.test(S);
}
$button = $("#sub_button");
$secteur = $("#secteur_select");
$machine = $("#machine_select");
$defaillance = $("#defaillance");
$urgence = $("#urgence");
$zone = $("#zone");

$bol1 = false;
$bol2 = false;
$bol3 = false;
$bol4 = false;
$bol5 = false;

function buttonvalidator(){
    if ($bol1 && $bol2 && $bol3 && $bol4 ){
        $button.removeAttr("disabled");
        $button.removeAttr("style");

        //alert("nice!");
    }else{

        $button.attr("style","cursor:no-drop");
        $button.attr("disabled",true);
    }
}

$machine.on("change", validatemachine);
function validatemachine(){
    const $machineval =  $machine.val();
    const $warn = $("#warn");

    if(checkString($machineval) ){
        $warn.removeClass(" fa-exclamation-triangle");
        $warn.addClass("fa-check");
        $bol1 = true;
        buttonvalidator();
    }else{
        $warn.removeClass(" fa-check");
        $warn.addClass("fa-exclamation-triangle");
        $warn.setAttribute("class","fa fa-exclamation-triangle")
        $bol1 = false;
        buttonvalidator();
    }
}

$zone.on("change", validatezone);
function validatezone(){
    const $zoneval =  $zone.val();
    const $warnzone = $("#warnzone");

    if(checkString($zoneval) ){
        $warnzone.removeClass(" fa-exclamation-triangle");
        $warnzone.addClass("fa-check");
        $bol5 = true;
        //buttonvalidator();
    }else{
        $warnzone.removeClass(" fa-check");
        $warnzone.addClass("fa-exclamation-triangle");
        $warnzone.setAttribute("class","fa fa-exclamation-triangle")
        $bol5 = false;
        //buttonvalidator();
    }
}

$defaillance.on("change",validatedef);
function validatedef(){
    const $deffaillanceval = $defaillance.val();
    const $warn1 = $("#warn1");

    if (checkString($deffaillanceval)){
        $warn1.removeClass("fa-exclamation-triangle");
        $warn1.addClass("fa-check");
        $bol2 = true;
        buttonvalidator();
    }else{
        $warn1.removeClass("fa-check");
        $warn1.addClass("fa-exclamation-triangle");
        $bol2 = false;
        buttonvalidator();
    }
}

$secteur.on("change",validatesecteur);
function validatesecteur(){
    const $secteurval = $secteur.val();
    const $warn2 = $("#warn2");

    if (checkString($secteurval)){
        $warn2.removeClass("fa-exclamation-triangle");
        $warn2.addClass("fa-check");
        $bol3 = true;
        buttonvalidator();
    }else{
        $warn2.removeClass("fa-check");
        $warn2.addClass("fa-exclamation-triangle");
        $bol3 = false;
        buttonvalidator();
    }
}

//$urgence.on("change", validateurgence);
jQuery("input[name='urgence']").on("change",validateurgence)
function validateurgence(){
    const $warn3 = $("#warn3");
    $warn3.removeClass("fa-exclamation-triangle");
    $warn3.addClass("fa-check");
    if ($('input[name="urgence"]:checked').val() == 'oui'){
        $('#dateLimite').removeAttr("hidden");

    }else{
        $('#dateLimite').attr("hidden","hidden");
    }


    $bol4 = true;
    buttonvalidator();
}