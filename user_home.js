
/*******************************************************************
 * Funzione che mostra contenuto nella modale
 *******************************************************************/
function showContent(){
    const nodeList = document.querySelectorAll('[data-elem="idGame"]');
    const infoBox=document.querySelector('[data-elem="infoBox"]')
    const array=Array.from(nodeList)
    //elementi per info giochi
    
    //span nome gioco
    const name=document.createElement('span')
    name.textContent= array[currentIndex].parentNode.querySelector('.nameGame').textContent
    name.dataset.elem="nameModal"

    //div generale
    const game=document.createElement('div')
    game.dataset.elem="gameModal"

    //immagine gioco
    const img=document.createElement('img')
    img.src=array[currentIndex].parentNode.querySelector('.imgGame').src
    img.dataset.elem="imgModal"

    // div che contiene la descrizione del gioco
    const gameDesc=document.createElement('div')
    gameDesc.dataset.elem="gameDescModal"

    //span data rilascio gioco
    const releaseDate=document.createElement('span')
    if(array[currentIndex].parentNode.querySelector('[data-elem="relDateGame"]').textContent==="Da definire"){
        releaseDate.textContent="Data di rilascio: Da Definire"
    }else{
        const timestampSec=parseInt(array[currentIndex].parentNode.querySelector('[data-elem="relDateGame"]').textContent)
        const date=new Date(timestampSec * 1000)
        const day = date.getDate().toString().padStart(2, "0"); // Ottengo il giorno e lo formatto con 2 cifre, aggiungendo lo zero iniziale se necessario
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Ottengo il mese (nota: i mesi in JavaScript sono indicizzati da 0 a 11)
        const year = date.getFullYear().toString(); // Ottengo l'anno
        releaseDate.textContent="Data di rilascio: "+ day + "/" + month + "/" + year
    }
    //span generi
    const genres=document.createElement('span')
    genres.textContent="Generi: " + array[currentIndex].parentNode.querySelector('[data-elem="genGame"]').textContent

    //span piattaforme
    const platforms=document.createElement('span')
    platforms.textContent="Piattaforme: " + array[currentIndex].parentNode.querySelector('[data-elem="platGame"]').textContent

    infoBox.innerHTML=""

    infoBox.appendChild(name)
    infoBox.appendChild(game)
    game.appendChild(img)
    game.appendChild(gameDesc)
    gameDesc.appendChild(releaseDate)
    gameDesc.appendChild(platforms)
    gameDesc.appendChild(genres)
}

function previousButton(){
    currentIndex--
    if (currentIndex < 0) {
        currentIndex = document.querySelectorAll('.divGame').length - 1;
      }
    showContent()
}   
function nextButton(){
    currentIndex++
    if (currentIndex >= document.querySelectorAll('.divGame').length) {
        currentIndex = 0;
      }
      showContent()
}  

function closeModal(event){
    console.log('modale chiusa')
    mod_over=event.currentTarget.parentNode
    document.body.classList.remove("no-scroll")
    mod_over.remove()
}

function openModal(event){
    console.log('modale aperta')
    const nodeList = document.querySelectorAll('[data-elem="idGame"]');
    const array=Array.from(nodeList)
    const id= event.currentTarget.querySelector('[data-elem="idGame"]')
    currentIndex=array.findIndex(element => element === id)

    console.log(currentIndex)
    //modale
    const mod_over=document.createElement('div')
    mod_over.dataset.elem="mod_over"
    document.body.classList.add("no-scroll")
    mod_over.style.top = window.pageYOffset + 'px';

    //box che contiene info sul gioco
    const infoBox=document.createElement('div')
    infoBox.dataset.elem="infoBox"

    //Pulsante close modal
    const close = document.createElement('img')
    close.src="image/cross.svg"
    close.dataset.elem="close"
    close.addEventListener("click", closeModal)  

    //pulsanti back e forward
    const back = document.createElement('img')
    back.src="image/back.svg"
    back.dataset.elem="back"
    back.addEventListener("click", previousButton)
    const forward = document.createElement('img')
    forward.src="image/forward.svg"
    forward.dataset.elem="forward"
    forward.addEventListener("click", nextButton)

    //append elementi modale
    mod_over.appendChild(close)
    mod_over.appendChild(back)
    mod_over.appendChild(forward)
    mod_over.appendChild(infoBox)
    document.body.appendChild(mod_over)
    showContent()
}


function clickLike(event){
    if(event.currentTarget.dataset.image==="clicked"){
        event.currentTarget.src="image/up.svg"
        event.currentTarget.dataset.image="unclicked"
        deleteLike(event.currentTarget.parentNode)
    } else if(event.currentTarget.dataset.image==="unclicked"){
        event.currentTarget.src="image/up_clicked.svg"
        event.currentTarget.dataset.image="clicked"
        saveLike(event.currentTarget.parentNode)
    }    
    event.stopPropagation()    
}
function onSaveGame(json){
    console.log(json.ans)
}
function saveLike(game){
    const formData = new FormData();
    formData.append('id', game.querySelector('[data-elem="idGame"]').textContent);
    formData.append('name', game.querySelector('.nameGame').textContent);
    formData.append('releaseDate', game.querySelector('[data-elem="relDateGame"]').textContent);
    formData.append('cover', game.querySelector('.imgGame').src);
    formData.append('genres', game.querySelector('[data-elem="genGame"]').textContent);
    formData.append('platforms', game.querySelector('[data-elem="platGame"]').textContent);
    fetch("save_game.php", {method: 'post', body: formData}).then(onResponse).then(onSaveGame);
}
function ondeleteLike(json){
    console.log(json.ans)
}
function deleteLike(game){
    const game_id = game.querySelector('[data-elem="idGame"]').textContent
    fetch("deleteLike.php?game_id="+game_id).then(onResponse).then(ondeleteLike);
}
function onLoadLike(json){
    const divs = document.querySelectorAll('.divGame');
    const like=document.createElement('img')
    if(json.exists){ 
        like.src="image/up_clicked.svg"
        like.dataset.image="clicked"
    }else {
        like.src="image/up.svg"
        like.dataset.image="unclicked"
    }
    like.classList.add('like')
    like.addEventListener("click", clickLike)
    for(const div of divs){
        if(div.querySelector('[data-elem=idGame]').textContent === json.idGame){
            div.appendChild(like)
        }
    }   
}
function onJson(json){
    document.body.classList.remove('progress')
    document.querySelector('#searchLine').textContent=""
    document.querySelector('#welcome').textContent=""
    console.log(json)
    
    if(json.length===0){
        document.querySelector('#searchLine').textContent="Nessun gioco trovato"
    }else{
        const section=document.querySelector('section')
        for(const game of json){
            
            // container
            const divGame=document.createElement('div')
            divGame.classList.add('divGame')
            divGame.addEventListener("click", openModal)

            //immagine gioco
            const imgGame=document.createElement('img')
            imgGame.classList.add('imgGame')
            imgGame.src=game.cover

            // nome gioco
            const nameGame=document.createElement('span')
            nameGame.classList.add('nameGame')
            nameGame.textContent=game.name

            
            // id gioco (non viene visualizzato)
            const idGame=document.createElement('span')
            idGame.classList.add('hidden')
            idGame.dataset.elem="idGame"
            idGame.textContent=game.id

            // button like
            fetch("checkLike.php?game_id="+game.id).then(onResponse).then(onLoadLike)

            // stringa piatatforme (non viene visualizzata)
            const platforms=document.createElement('span')
            platforms.classList.add('hidden')
            platforms.dataset.elem="platGame"
            if("platforms" in game){
                platforms.textContent=game.platforms.toString().replace(/,/g, ', ');
            }else{
                platforms.textContent= "Da definire"
            }
            //stringa generi (non viene visualizzata)
            const genres=document.createElement('span')
            genres.classList.add('hidden')
            genres.dataset.elem="genGame"
            if("genres" in game){
                genres.textContent=game.genres.toString().replace(/,/g, ', ');
            }else{
                genres.textContent= "Da definire"
            }
            //stringa data rilascio(non viene visualizzata)
            const releaseDate=document.createElement('span')
            releaseDate.classList.add('hidden')
            releaseDate.dataset.elem="relDateGame"
            if("first_release_date" in game){
                releaseDate.textContent=game.first_release_date
            }else{
                releaseDate.textContent="Da definire"
            }


            section.appendChild(divGame)
            divGame.appendChild(imgGame)
            divGame.appendChild(nameGame)
            divGame.appendChild(idGame)
            divGame.appendChild(platforms)
            divGame.appendChild(genres)
            divGame.appendChild(releaseDate)
            
        }
    }
	
}

function onResponse(response){
    return response.json()
}
function search(event){
    event.preventDefault()
    const search=document.querySelector("#searchBar").value
    if (search!==""){
        document.querySelector('#searchLine').textContent="Attendi il caricamento dei giochi..."
        document.querySelector('section').innerHTML=""
        document.body.classList.add('progress')
        fetch('search_content.php?q='+encodeURIComponent(search)).then(onResponse).then(onJson)
    }
}
let currentIndex;
document.querySelector("form").addEventListener("submit", search)
