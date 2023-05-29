function checkUsername(event){
    const username=event.currentTarget
    if(username.value.length===0){
        username.parentNode.classList.add('error')
    } else{
        username.parentNode.classList.remove('error')
    }
}
   
   
function checkPassword(event){
    const password=event.currentTarget
    if(password.value.length===0){
        password.parentNode.classList.add('error')
    } else{
        password.parentNode.classList.remove('error')
    }
}    
        
           
                


document.querySelector('[data-elem="username"] input').addEventListener('blur',checkUsername)
document.querySelector('[data-elem="password"] input').addEventListener('blur',checkPassword)