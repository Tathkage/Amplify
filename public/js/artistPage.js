console.log("Hello from script.js");

// new song pop up function
function newSongPopup() {
    document.getElementById("popup").style.display = "block";
}

function closeNewSongPopup() {
    document.getElementById("popup").style.display = "none";
}

// new album pop up functions
function newAlbumPopup() {
    document.getElementById("popup2").style.display = "block";
}

function closeNewAlbumPopup() {
    document.getElementById("popup2").style.display = "none";
}

// functions for edit album popup
function editAlbumPopup(album_id) {
    document.getElementById("id" + album_id).style.display = "block";
}

function closeEditAlbumPopup(album_id) {
    document.getElementById("id" + album_id).style.display = "none";
}

function showChangeNameForm() {
    if (document.getElementById("nf").style.display == "none")
    {
        document.getElementById("nameButton").textContent = "Keep Stage Name";
        document.getElementById("nf").style.display = "block";
    }

    else {
        document.getElementById("nameButton").textContent = "Change Stage Name";
        document.getElementById("nf").style.display = "none";
    }
}

// functionality for adding new song form to album form

const addSongButton = document.querySelector('#add-song');
const songList = document.querySelector('#song-list');

addSongButton.addEventListener('click', () => {
    const songEntry = document.createElement('div');
    songEntry.classList.add('song-entry');

    const songTitleInput = document.createElement('input');
    songTitleInput.type = 'text';
    songTitleInput.name = 'song_title[]';
    songTitleInput.id = 'song_title'
    // songTitleInput.required = true;

    const lengthInput = document.createElement('input');
    const br = document.createElement("br");

    lengthInput.type = 'text';
    lengthInput.name = 'length[]';
    lengthInput.id = 'length;'
    // lengthInput.required = true;

    const inputLabel = document.createElement('label');
    inputLabel.setAttribute('for', 'song_title');
    songEntry.appendChild(inputLabel).textContent = 'Song Title:';
    songEntry.appendChild(songTitleInput);

    const lengthLabel = document.createElement('label');
    lengthLabel.setAttribute('for', 'length');
    songEntry.appendChild(lengthLabel).textContent = 'Length:';
    songEntry.appendChild(lengthInput);
    songEntry.appendChild(br);

    songList.appendChild(songEntry);
    songList.appendChild(br);
});