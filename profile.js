function onInput(event){
    event.currentTarget.parentNode.querySelector('input').click()
}

function onModifyPhoto(json){
    console.log(json.ans)
    location.reload()
}
function changePhoto(event){
    const photo=event.currentTarget.files[0]
    const error=document.querySelector('#error')
    console.log(photo)
    if(!photo){
        error.textContent="Nessun file selezionato"
        error.classList.remove('hidden')
    }else{ 
        const ext=photo.name.split('.').pop() // elimina tutto quello che c'è prima del punto nel nome del file
        if(photo.size>5242880){
            error.classList.remove('hidden')
            error.textContent="Massimo 5 MB"
        }else if(!['jpeg', 'jpg', 'png', 'gif'].includes(ext)){
            error.classList.remove('hidden')
            error.textContent="Formato non supportato"
        }else{
            const formData = new FormData();
            formData.append('photo', photo);
            error.classList.add('hidden')
            fetch('modifyPhoto.php', {method: 'post', body: formData}).then(onResponse).then(onModifyPhoto)
        }
    }
}
function onDislike(json){
    console.log(json.ans)
    location.reload()
}
function onClickDislike(event){
    const response=confirm("Vuoi rimuovere questo gioco?")
    const idGame=event.currentTarget.parentNode.dataset.idGame
    if (response){
       fetch('deleteLike.php?game_id='+idGame).then(onResponse).then(onDislike)
    }
}
function onGames(json){
    const article=document.querySelector('article')
    console.log(json)
    if (json.length === 0){
        const title=document.querySelector('#title')
        title.textContent="Non hai ancora giochi tra i preferiti"
        article.appendChild(title)
    }else{
        for(let i = 0; i < json.length; i++){
            const divGame= document.createElement('div')
            divGame.dataset.idGame=json[i]['id']
            if(i%2 === 0){
                divGame.classList.add('red')
            }else{
                divGame.classList.add('blue')
            }
            const thumb = document.createElement('img')
            thumb.src =json[i]['cover']
            thumb.classList.add('thumb')

            const infoGame=document.createElement('div')
            infoGame.classList.add('infoGame')
            const nameGame=document.createElement('span')
            nameGame.textContent="Nome: "+json[i].name

            const releaseDate=document.createElement('span')
            if (json[i].releaseDate !== '0'){
                const timestampSec=json[i].releaseDate
                const date=new Date(timestampSec * 1000)
                const day = date.getDate().toString().padStart(2, "0"); // Ottengo il giorno e lo formatto con 2 cifre, aggiungendo lo zero iniziale se necessario
                const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Ottengo il mese (nota: i mesi in JavaScript sono indicizzati da 0 a 11)
                const year = date.getFullYear().toString(); // Ottengo l'anno
                releaseDate.textContent="Data di rilascio: "+ day + "/" + month + "/" + year
            } else{
                releaseDate.textContent="Data di rilascio: Da definire"
            }
            
            const genres=document.createElement('span')
            genres.textContent="Generi: "+json[i].genres
            const platforms=document.createElement('span')
            platforms.textContent="Piattaforme: "+ json[i].platforms

            const dislike=document.createElement('img')
            dislike.src='image/dislike.svg'
            dislike.dataset.game="dislike"
            dislike.addEventListener("click", onClickDislike)

            article.appendChild(divGame)
            divGame.appendChild(thumb)
            divGame.appendChild(infoGame)
            divGame.appendChild(dislike)
            infoGame.appendChild(nameGame)
            infoGame.appendChild(releaseDate)
            infoGame.appendChild(genres)
            infoGame.appendChild(platforms)
        }
    }
    
}

function onResponse(response){
    return response.json()
}
fetch("dbGames.php").then(onResponse).then(onGames)
document.querySelector('#camera').addEventListener("click",  onInput)
document.querySelector('#profile input').addEventListener("change", changePhoto)
