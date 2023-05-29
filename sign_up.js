
//Controllo Nome
function checkName(event){
    const name = event.currentTarget
    console.log(event.currentTarget)
    if(name.value.length>0){
        name.parentNode.classList.remove('error')

    } else{
        name.parentNode.classList.add('error')

    }
}
//Controllo Cognome
function checkSurname(event){
    const surname = event.currentTarget
    console.log(event.currentTarget)
    if(surname.value.length>0){
        surname.parentNode.classList.remove('error')

    } else{
        surname.parentNode.classList.add('error')

    }
}//on Response per checkUsername e checkEmail
function onResponse(response){
    return response.json()
}

// Check locale dell'esistenza nel database dell'username inserito
function onJsonCheckUsername(json){
    const errorUserMessage = document.querySelector('[data-elem=username] div span')
    if(!json.exists){
        errorUserMessage.parentNode.parentNode.classList.remove('error')
    }else{
        errorUserMessage.textContent="Username già in uso"
        errorUserMessage.parentNode.parentNode.classList.add('error')
    }
}

function checkUsername(event) {
    const username=event.currentTarget
    if(!/^[a-zA-Z0-9_]{1,15}$/.test(username.value)) {
        username.parentNode.querySelector('span').textContent = "Lettere, numeri e underscore (15)";
        username.parentNode.classList.add('error');
    } else {
        fetch("checkUsername.php?q="+encodeURIComponent(username.value)).then(onResponse).then(onJsonCheckUsername);
    }    
}
// Verifica lunghezza password
function checkPassword(event){
    const password = event.currentTarget
    console.log(event.currentTarget)
    if(password.value.length>=8){
        password.parentNode.classList.remove('error')

    } else{
        password.parentNode.classList.add('error')

    }
}
// Conferma password
function checkConfPass(event){
    const confpass = event.currentTarget
    const pass= document.querySelector('[data-elem="password"] input')
    console.log(event.currentTarget)
    if(confpass.value===pass.value){
        confpass.parentNode.classList.remove('error')

    } else{
        confpass.parentNode.classList.add('error')

    }
}

// Check locale dell'esistenza nel database dell'email inserita
function onJsonCheckEmail(json){
    const errorUserMessage = document.querySelector('[data-elem=email] span')
    if(!json.exists){
        errorUserMessage.parentNode.parentNode.classList.remove('error')
    }else{
        errorUserMessage.textContent="Email già in uso"
        errorUserMessage.parentNode.parentNode.classList.add('error')
    }
}


function checkEmail(event){
    const email=event.currentTarget
    if(!/^[A-z0-9\.\+_-]+@[A-z0-9\._-]+\.[A-z]{2,6}$/.test(email.value)){
        email.parentNode.classList.add('error')
        email.parentNode.querySelector('span').textContent="Email non valida"
    }else{
        fetch("checkEmail.php?q="+encodeURIComponent(email.value)).then(onResponse).then(onJsonCheckEmail)
    }
}


// Verifica correttezza formato e dimensioni della foto profilo
function checkPhoto(event){
    const input=event.currentTarget
    const photo=input.files[0]
    if(!photo){
        document.querySelector('#file_name').textContent="Nessun file selezionato"
        input.parentNode.classList.remove('error')
    }else{ 
        document.querySelector('#file_name').textContent=photo.name + " --> Dimensione: " +(photo.size/1048576).toFixed(2) + " MB"
        const ext=photo.name.split('.').pop() // elimina tutto quello che c'è prima del punto nel nome del file
        if(photo.size>5242880){
            input.parentNode.classList.add('error')
            input.parentNode.querySelector('span').textContent="Massimo 5 MB"
        }else if(!['jpeg', 'jpg', 'png', 'gif'].includes(ext)){
            input.parentNode.classList.add('error')
            input.parentNode.querySelector('span').textContent="Formato non supportato"
        }else{
            input.parentNode.classList.remove('error')
        }
    }
}

// Funzione che associa il click del pulsante aggiunto al pulsante dell'input file
function chooseFile(event){
    event.preventDefault()
    document.querySelector('[data-elem=photo] input').click()
}

// Verifica correttezza data di nascita
function checkDate(event){
    const msecToDays=1000*60*60*24
    const date=event.currentTarget
    const dateValue=new Date(date.value).getTime()/msecToDays //giorni data selezionata da Epoch
    console.log(dateValue*msecToDays)
    const today=Date.now()/msecToDays // giorni da Epoch
    if(dateValue>today){
        date.parentNode.classList.add('error')
        date.parentNode.querySelector('span').textContent="Devi ancora nascere?"
    }else if(today-dateValue<= 4383){ //età minima 12 anni(4383 giorni)
        date.parentNode.classList.add('error')
        date.parentNode.querySelector('span').textContent="Devi avere almeno 12 anni"
    }else{
        date.parentNode.classList.remove('error')
    }
}



document.querySelector('[data-elem="name"] input').addEventListener('blur',checkName)
document.querySelector('[data-elem="surname"] input').addEventListener('blur',checkSurname)
document.querySelector('[data-elem="username"] input').addEventListener('blur',checkUsername)
document.querySelector('[data-elem="password"] input').addEventListener('blur',checkPassword)
document.querySelector('[data-elem="confpass"] input').addEventListener('blur',checkConfPass)
document.querySelector('[data-elem="email"] input').addEventListener('blur',checkEmail)
document.querySelector('[data-elem="photo"] input').addEventListener('change',checkPhoto)
document.querySelector('[data-elem="date"] input').addEventListener('change',checkDate)
document.querySelector('[data-elem="chooseButton"]').addEventListener('click',chooseFile)








