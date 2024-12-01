function fixHeader() {
    const textSize = document.querySelector('body > header > h1').clientWidth.toString();
    document.querySelector('body > header > div').style.width = textSize + "px"
    document.querySelector('body').style.paddingTop = document.querySelector('body > header').clientHeight.toString() + "px";
    document.querySelector('body').style.minHeight = "calc(" + window.innerHeight + "px - " + document.querySelector('body').style.paddingTop + ")";
}

fixHeader();
window.addEventListener('resize', fixHeader);