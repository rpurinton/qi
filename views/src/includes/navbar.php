<style>
  #search-clear {
    position: relative;
    right: 23px;
    top: 8px;
    color: light-gray;
    display: none;
    cursor: pointer;
  }

  .results-container {
    background-color: #111;
    min-width: 390px;
    max-width: 100%;
    height: 100%;
    max-height: 100%;
    overflow-x: hidden !important;
    overflow-y: scroll !important;
    display: none;
    border-radius: 10px;
    padding: 0;
    scrollbar-width: none;
    -ms-overflow-style: none;
    scroll-behavior: smooth;
  }

  .results-container::-webkit-scrollbar {
    display: none;
  }
</style>
<nav class="navbar p-0 fixed-top d-flex flex-row flex-nowrap" style="vertical-align: middle;">
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" style="padding: 0;" href="/"><i class="mdi mdi-home"></i></a>
    <a class="navbar-brand brand-logo-mini" style="padding: 0;" href="/"><img style='margin-left: 15px; margin-right:10px; width: 25px; height: 25px;  vertical-align: text-top;' src='/assets/images/icon/qi_icon.png'></a>
  </div>
  <div style="padding-left: 0px;" class=" navbar-menu-wrapper flex-grow d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <script>

    </script>
    <ul class="navbar-nav w-100">
      <li class="nav-item w-100">
        <form style="display: flex; white-space: nowrap; vertical-align: middle; margin: 3px !important; max-width: 400px;" class="nav-link mt-2 mt-md-0 d-lg-flex search">
          <input style="width: calc(100% - 16px); max-width: 310px;" id='search' type=" text" class="form-control" placeholder="Search..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></input>
          <span id='search-clear' class="mdi mdi-close-circle"></span></input>
        </form>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown d-lg-block">
        <a class="nav-link btn create-new-button" id="createbuttonDropdown" href="/new-idea">+ New Idea</a>
      </li>
      <?php
      if (!$this->loggedin) {
      ?>
        <li class="nav-item dropdown">
          <a href="/login">
            <div class="navbar-profile" style="flex-wrap: nowrap; white-space: nowrap; display: flex; align-items: center;">
              <img class="img-xs rounded-circle" src="https://cdn.discordapp.com/embed/avatars/0.png" alt="Discórd">
              <p class="mb-0 d-none d-sm-block navbar-profile-name">&nbsp;Login</p>
              <i class="mdi mdi-menu-down d-none d-sm-block"></i>
            </div>
          </a>
        </li>
      <?php
      } else {
      ?>
        <li class="nav-item dropdown">
          <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
            <div class="navbar-profile" style="flex-wrap: nowrap; white-space: nowrap; display: flex; align-items: center;">
              <img class="img-xs rounded-circle" src="<?= $this->user["discord_avatar"] ?>" alt="<?= $this->user["discord_username"] ?>">
              <p class="mb-0 d-none d-sm-block navbar-profile-name">&nbsp;<?= $this->user["discord_global_name"] ?></p>
              <i class="mdi mdi-menu-down d-none d-sm-block"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
            <div class="dropdown-divider"></div>
            <!-- Settings -->
            <a href="/settings" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-settings text-success"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject mb-1">Settings</p>
              </div>
            </a>
            <!-- Logout -->
            <a href="/logout" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-logout text-danger"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject mb-1">Logoff</p>
              </div>
            </a>
            <div class="dropdown-divider"></div>
          </div>
        </li>
      <?php
      }
      ?>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>
<script>
  var lastSearch = "";
  var searchInput = document.getElementById("search");
  var clearButton = document.getElementById("search-clear");
  var resultsContainer = document.getElementById("results-container");
  var resultsDiv;
  var timeoutIds = [];
  var intervalIds = [];
  var start_time = 0;
  var controllers = [];
  var thinking = false;
  var inactive = false;
  var wildcard = false;
  var characters = true;
  var clans = true;
  var servers = true;
  var regions = true;

  // Add a keyup event listener to the input element
  searchInput.addEventListener("keyup", function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
    }
    search(true);
  });

  function inactivesearch(value) {
    inactive = value;
    lastSearch = "";
    search(false);
  }

  function wildcardsearch(value) {
    wildcard = value;
    lastSearch = "";
    search(false);
  }

  function characterssearch(value) {
    characters = value;
    lastSearch = "";
    search(false);
  }

  function clanssearch(value) {
    clans = value;
    lastSearch = "";
    search(false);
  }

  function serverssearch(value) {
    servers = value;
    lastSearch = "";
    search(false);
  }

  function regionssearch(value) {
    regions = value;
    lastSearch = "";
    search(false);
  }

  function search(wait) {
    if (wait) {
      delay = 500;
    } else {
      delay = 1;
    }
    var query = searchInput.value;
    if (query) {
      clearButton.style.display = "block";
    } else {
      clearButton.style.display = "none";
    }

    if (query != lastSearch) {
      if (query.length >= 2) {
        lastSearch = query;
        resultsContainer = document.getElementById("results-container");
        resultsContainer.style.display = "block";
        if (wait) resultsContainer.innerHTML = "<pre id='loadtime' style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>aguardando entrada...</pre>";
        else resultsContainer.innerHTML = "<pre id='loadtime' style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>procurando...0.0s</pre>";
        thinking = true;
        if (timeoutIds) {
          timeoutIds.forEach(function(id) {
            clearTimeout(id);
          });
          timeoutIds = [];
        }
        if (intervalIds) {
          intervalIds.forEach(function(id) {
            clearInterval(id);
          });
          intervalIds = [];
        }
        if (controllers) {
          controllers.forEach(function(controller) {
            try {
              controller.abort();
            } catch (e) {}
          });
          controllers = [];
        }
        controller = new AbortController();
        controllers.push(controller);
        timeoutIds.push(setTimeout(() => {
          start_time = new Date().getTime();
          intervalIds.push(setInterval(function() {
            var time = new Date().getTime() - start_time;
            document.getElementById("loadtime").innerHTML = "procurando... " + (time / 1000).toFixed(1) + "s...";
          }, 100));
          // Send a POST request to /views/search/results.php with query as parameter
          try {
            fetch("/views/search/results.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + query + "&inactive=" + inactive + "&wildcard=" + wildcard + "&characters=" + characters + "&clans=" + clans + "&servers=" + servers + "&regions=" + regions,
                signal: controller.signal // Pass the signal property of the AbortController instance
              })
              .then(function(response) {
                // Return the response text
                return response.text();
              })
              .then(function(data) {
                // Create a div element
                var element = document.getElementById("resultsDiv");
                if (element && element.parentNode) {
                  element.parentNode.removeChild(element);
                }
                resultsDiv = document.createElement("div");
                resultsDiv.id = "resultsDiv";
                // Set its innerHTML to data
                resultsDiv.innerHTML = data;
                // Append it to document body
                resultsContainer = document.getElementById("results-container");
                if (intervalIds) {
                  intervalIds.forEach(function(id) {
                    clearInterval(id);
                  });
                  intervalIds = [];
                }
                resultsContainer.innerHTML = "";
                resultsContainer.appendChild(resultsDiv);
                resultsDiv.style.width = "100%";
                resultsDiv.style.height = "100%";
                resultsDiv.style.backgroundColor = "#111";
                thinking = false;
              })
              .catch(err => {});
          } catch (e) {}
        }, delay)); // Wait for 1ms before fetching

      } else {
        if (timeoutIds) {
          timeoutIds.forEach(function(id) {
            clearTimeout(id);
          });
          timeoutIds = [];
        }
        if (controllers) {
          controllers.forEach(function(controller) {
            controller.abort();
          });
          controllers = [];
        }
        thinking = false;
        document.getElementById("results-container").style.display = "none";
        // Remove resultsDiv from document body if it exists
        var element = document.getElementById("resultsDiv");
        if (element && element.parentNode) {
          element.parentNode.removeChild(element);
        }
      }
    }
  }


  // Add a click event listener to span element
  clearButton.addEventListener("click", function() {
    if (timeoutIds) {
      timeoutIds.forEach(function(id) {
        clearTimeout(id);
      });
      timeoutIds = [];
    }
    if (controllers) {
      controllers.forEach(function(controller) {
        controller.abort();
      });
      controllers = [];
    }
    searchInput.value = "";
    clearButton.style.display = "none";
    document.getElementById("results-container").style.display = "none";
    w
    // Remove resultsDiv from document body if it exists
    var element = document.getElementById("resultsDiv");
    if (element && element.parentNode) {
      element.parentNode.removeChild(element);
    }
  });

  // click the clear button if the user presses esc
  document.addEventListener("keydown", function(event) {
    if (event.keyCode == 27) {
      clearButton.click();
    }
  });

  // prevent enter from submitting the form
  document.addEventListener("keydown", function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
    }
  });
</script>
<div class="main-panel">
  <div id="content-wrapper" class="content-wrapper" style='border-radius: 10px; display: flex; height: 100%;'>
    <div id="results-container" class="results-container"></div>