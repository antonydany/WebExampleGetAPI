<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Búsqueda de Pokemon</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

  <div class="max-w-md w-full mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Búsqueda de Pokemon</h2>

    <div class="relative">
      <input type="text" id="searchInput" placeholder="Escribe aquí..." class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 w-full bg-gray-800 text-gray-300"> 
      <div id="searchResults" class="absolute z-10 mt-1 w-full bg-gray-800 border border-gray-700 rounded-b-md shadow-lg hidden">
      </div>
    </div>
  </div>

  <script>
    var debounceTimer;
    var searchCache = {}; // Objeto para almacenar resultados en caché

    function search() {
      var input = $("#searchInput").val().toLowerCase();

      // Verificar si los resultados están en caché
      if (searchCache[input]) {
        displayResults(searchCache[input]);
      } else {
        // Cancelar el temporizador anterior si existe
        clearTimeout(debounceTimer);

        // Iniciar un nuevo temporizador para retrasar la búsqueda
        debounceTimer = setTimeout(function() {
          // Realizar una solicitud GET a la API de PokeAPI
          $.get("https://pokeapi.co/api/v2/pokemon/?limit=20&offset=20", function(data) {
            // Extraer los nombres de las habilidades de los resultados
            var results = data.results.map(function(item) {
              return item.name;
            });

            // Filtrar los nombres de habilidades basados en la entrada del usuario
            var filteredResults = results.filter(function(item) {
              return item.toLowerCase().includes(input);
            });

            // Almacenar nuevos resultados en caché
            searchCache[input] = filteredResults;

            // Mostrar los resultados
            displayResults(filteredResults);
          });
        }, 300); // Tiempo de espera en milisegundos
      }
    }

    function displayResults(results) {
      //lista de resultados
      var output = "<ul class='py-2'>";
      results.forEach(function(item) {
        output += "<li class='px-4 py-2 cursor-pointer hover:bg-gray-700 resultItem'>" + item + "</li>"; // Estilos específicos para el modo oscuro
      });
      output += "</ul>";

      // Mostrar los resultados
      $("#searchResults").html(output);
      $("#searchResults").toggleClass("hidden", $("#searchInput").val() === "");
    }

    $(document).on("keyup", "#searchInput", function() {
      search();
    });

    $("#searchInput").on("focus", function() {
      search();
    });

    $(document).on("click", ".resultItem", function() {
      var selectedItem = $(this).text();
      $("#searchInput").val(selectedItem);
      $("#searchResults").addClass("hidden");
    });

    $(document).on("click", function(e) {
      if (!$(e.target).closest("#searchInput, #searchResults").length) {
        $("#searchResults").addClass("hidden");
      }
    });
  </script>

</body>
</html>
