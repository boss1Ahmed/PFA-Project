
    function validateEmail(email) {
    var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return re.test(email);
}
    function validate() {
    var $result = $("#result_mail");
    var email = $("#inputEmailAddress").val();
    $result.text("");

    if (validateEmail(email)) {
    $result.text(email + " is valid");
    $result.css("color", "blue");
        return true ;
} else {
    $result.text(email + " is not valid");
    $result.css("color", "red");
        return false;
}
    return false;
}
    $("#inputEmailAddress").on("input", validate);


    function checkString(S){
        const re = /^[a-zA-Z]+$/;

        return re.test(S);
    }

    function validateNom() {
        const $result = $("#result_nom");
        const nom = $("#inputLastName").val();
        $result.text("");

        if(checkString(nom)){
            $result.text(nom + " is valid ");
            $result.css("color", "blue");
            return true ;
        } else {
            $result.text(nom + " is not valid ");
            $result.css("color", "red");
            return false ;
        }
        return false;
    }



    $("#inputLastName").on("input", validateNom);



    function checkPrenom(S){
        const re = /^[a-zA-Z]+$/;

        return re.test(S);
    }

    function validatePrenom() {
        const $result = $("#result_prenom");
        const prenom = $("#inputFirstName").val();
        $result.text("");

        if(checkPrenom(prenom)){
            $result.text(prenom + " is valid ");
            $result.css("color", "blue");
            return true ;
        } else {
            $result.text(prenom + " is not valid ");
            $result.css("color", "red");
            return false ;
        }
        return false;
    }



    $("#inputFirstName").on("input", validatePrenom);


    function checknum(N){
        const re = /^(06|07)[0-9]{8}$/;

        return re.test(N);
    }

    function validateNum(){
        const $result = $("#result_tel");
        const num = $("#inputPhone").val();
        $result.text("");

        if(checknum(num)){
            $result.text(num + " is valid ");
            $result.css("color", "blue");
            return true ;
        } else {
            $result.text(num + " is not valid ");
            $result.css("color", "red");
            return false ;
        }
        return false;

    }
    $("#inputPhone").on("input", validateNum);




    function submitTest(){
        const $a = $("#enregister");

        if(  (validateNom()) && (validatePrenom()) && (validate()) &&(validateNum()) ){
            $a.removeAttr("disabled");

        }else{
            test();
        }
        window.setTimeout( submitTest, 500 );

    }
    function test(){
        $("#enregister").attr('disabled', true);
    }
    $("#inputEmailAddress").on("input", submitTest);

