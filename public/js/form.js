document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('mapForm');
    let searchField = document.getElementById('searchTextField');
    let currentFocus;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let searchValue = searchField.value.trim();

        let response = await fetch(this.action + '/' + searchValue);
        if (response.ok) {
            document.getElementById('googleMap').setAttribute('src', await response.text());
        }

        if (searchValue) {
            let saveResponse = await fetch('/map/request/save', {
                method: 'POST',
                body: new FormData(this)
            });
        }
    });

    searchField.addEventListener('input', async function(e) {
        let query = this.value.trim();
        if (query) {
            let response = await fetch('/map/request/autocomplete/' + query);
            if (response.ok) {
                var places = await response.json();
            }

            closeAllLists();

            currentFocus = -1;
            let parentBlock = document.createElement("div");

            parentBlock.setAttribute("id", this.id + "-autocomplete-list");
            parentBlock.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(parentBlock);

            for (let i = 0; i < places.length; i++) {
                let childBlock = document.createElement("div");

                childBlock.innerHTML = "<span>" + places[i] + "</span>";
                childBlock.innerHTML += "<input type='hidden' value='" + places[i] + "'>";

                childBlock.addEventListener("click", function(e) {
                    searchField.value = this.getElementsByTagName("input")[0].value;

                    closeAllLists();

                    form.requestSubmit();
                });
                parentBlock.appendChild(childBlock);

            }
        }
    });

    searchField.addEventListener("keydown", function(e) {
        var block = document.getElementById(this.id + "-autocomplete-list");
        if (block) {
            block = block.getElementsByTagName("div");
        }
        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(block);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(block);
        } else if (e.key === 'Enter') {
            if (currentFocus > -1) {
                if (block) {
                    block[currentFocus].click();
                }
            }
        }
    });

    function addActive(block) {
        if (!block) {
            return false;
        }
        removeActive(block);
        if (currentFocus >= block.length) {
            currentFocus = 0;
        }
        if (currentFocus < 0) {
            currentFocus = (block.length - 1);
        }
        block[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(block) {
        for (var i = 0; i < block.length; i++) {
            block[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        var blockList = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < blockList.length; i++) {
            if (elmnt != blockList[i] && elmnt != searchField) {
                blockList[i].parentNode.removeChild(blockList[i]);
            }
        }
    }

    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
});