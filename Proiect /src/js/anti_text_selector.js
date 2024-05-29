function disabletextselect(i){
    return false
}

function renabletextselect(){
    return true
}

document.onselectstart=new Function ("return false")

if (window.sidebar){
    document.onmousedown=disabletextselect
    document.onclick=renabletextselect
}