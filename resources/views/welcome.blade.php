<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#posts').click(function (e) { 
        e.preventDefault();
        $.ajax({
          type: "get",
          url: "/post",
          success: function (response) {
            $('#postBody').empty();
            response.forEach(function (item) {
              var name = document.createElement("span");  
              name.innerHTML = item['user_name'];
              name.setAttribute('class', 'text-sm font-semibold text-gray-400 ');

              var divName = document.createElement("div");  
              divName.appendChild(name);
              divName.setAttribute('class', 'flex items-center space-x-2 rtl:space-x-reverse');
              divName.addEventListener('click', function () {
                location.href = "/user/" + item['user_id'];
              });

              var text = document.createElement("p");
              text.innerHTML = item['text'];
              text.setAttribute('class', 'text-sm font-normal py-2.5 text-gray-900 dark:text-white');

              var div = document.createElement("div");
              div.setAttribute('class', 'flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl dark:bg-gray-700 mt-2');
              div.appendChild(divName);
              div.appendChild(text);

              document.getElementById('postBody').appendChild(div);
              
          });
            
          },
          error: function (response) {
            alert('something bad happend');
            console.log(response);
            
          }
          
        });
      });
      $('#sendPost').click(function (e) { 
        $('#postBody').empty();
        e.preventDefault();
        var textarea = document.createElement("textarea");  
        textarea.setAttribute('class', 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500');
        textarea.setAttribute('id', 'textarea');

        var button = document.createElement("button");  
        button.innerHTML = 'Create new post';
        button.setAttribute('class', 'text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ');
        button.setAttribute('type', 'button');
        button.setAttribute('action', '/post');
        button.setAttribute('method', 'post');
        button.setAttribute('id', 'submitPost');


        var label = document.createElement("label");  
        label.innerHTML = 'Type your message';
        label.setAttribute('class', 'block mb-2 text-sm font-medium text-gray-900 dark:text-white');

        var form = document.createElement("form");  
        form.setAttribute('class', 'max-w-sm mx-auto');
        form.setAttribute('id', 'postForm');
        form.appendChild(label);
        form.appendChild(textarea);
        form.appendChild(button);
        document.getElementById('postBody').appendChild(form);
      });
      $(document).on('click','#submitPost',function(){
        console.log();
        
        $.ajax({
          type: "post",
          url: "post",
          data: {
            // tags: 
            // image:
            text: document.getElementById('textarea').value,
          },
          statusCode: {
            401: function (response) { alert(response.responseJSON['message']) }
          },
          success: function (response) {
            console.log(response)
          },
          error: function (response) {
            console.log(response)
          }
        });
      });
        
      });
  </script>

</head>

<body>
<nav class="bg-gray-800">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <div class="flex flex-1 items-left justify-left sm:items-stretch sm:justify-start">
        <div class="sm:ml-2 sm:block">
          <div class="flex">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-299 hover:bg-gray-700 hover:text-white" -->
            <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white" id="posts">Posts</button>
            <a href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white" id="sendPost">Send Post</a>
          </div>
        </div>
      </div>

        @guest
        <div class="relative ml-3">
          <div>
            <a href="/login" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
            <a href="/signup" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Signup</a>
          </div>
        </div>
        @endguest
        @auth
        <div class="relative ml-3">
          <div >
            <form action="/logout" method="POST">
              @method("delete")
              <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Logout</button>
              <a href="/user/{{Auth::user()->id}}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">profile</a>
            </form>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </div>

</nav>

<div>
<div id="postBody"></div>
</div>

</div>

</body>

</html>