function anti_right()
{
    alert('Right Click not authorized!');
	return(false);
}
document.oncontextmenu = anti_right;