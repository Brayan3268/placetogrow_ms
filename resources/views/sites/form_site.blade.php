<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('View Form Site') }}
      </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Form Site</title>
    </x-slot>

    @section('content')
      <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">Complete the form for pay</h1>

        <form id="payForm" method="POST" class="max-w-lg mx-auto mt-10" action="{{ route('payment.store') }}">
          @csrf
          @foreach($sites_fields as $input)
            <div class="mb-6">
              <label for="{{ $input['name'] }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $input['name_user_see'] }}</label>
              @if($input['type'] === 'text')
                <input type="text" id="{{ $input['name'] }}" name="{{ $input['name'] }}"  @if(!$input['is_optional']) required @endif class="bg-gray-200 form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('{{ $input->name }}') border-red-500 @enderror">
              @elseif($input['type'] === 'number')
                <input type="number" min="1" id="{{ $input['name'] }}" name="{{ $input['name'] }}"  @if($input['is_optional']) required @endif class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('{{ $input->name }}') border-red-500 @enderror">
              @elseif($input['type'] === 'select')
                @php
                  $options = explode(',', $input->values);
                @endphp
                <select id="{{ $input['name'] }}" name="{{ $input['name'] }}" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error(' {{ $input->name }} ') border-red-500 @enderror" required>
                  <option value="">Select an option</option>
                  @foreach($options as $option)
                    <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          @endforeach
          <button type="submit" id="submitForm" class="my-button">Submit</button>
        </form>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const form = document.getElementById('payForm');
          form.addEventListener('submit', function(event) {
            event.preventDefault();
        
            const formData = new FormData(form);
                    
            let alertMessage = 'Form Data:\n';

            for (const entry of formData.entries()) {
              alertMessage += `${entry[0]}: ${entry[1]}\n`;
            }

            alert(alertMessage);

            var json_file = obtener_json_sesion()

            json_file.payment.reference = "test";
            json_file.payment.amount.currency = "COP";
            json_file.payment.amount.total = 1000;
            json_file.payment.description = "assdadass";
            json_file.locale = "es_CO";
            json_file.expiration = 15;
            json_file.returnUrl = "test";
            json_file.ipAddress = "189.139.60.189";
            json_file.userAgent = "iomabeCO Place_to_Pay_Colombia";

            let url = 'https://checkout-test.placetopay.com/api/session';
            alert("va a entrar");
            axios.post(url, json_file)
              alert("entro 1");
              .then(response => {
              alert("entro 2");

              let status = response.data.status.status
              if(status === "PENDING"){
                alert("PENDING")
              }
              if(status === "APPROVED"){
                alert("APPROVED")
              }
              console.log(response)
              console.log(status)

              //window.location.href = response.data.processUrl;
            })
            .catch(error => {
              let error_str = String(error.request.response)
              if(error_str.includes("Error al recuperar la transaccion")){
                alert("Error")
              }
            });
          });

        });

        function obtener_json_sesion(){
          var login = ""
          var nonce = generarUUID();
          let nonceB64 = btoa(nonce.toString());

          var seed2 = new Date().toISOString()

          var secretkey = "";

          var trankey_woc = (nonce + seed2 + secretkey) //woc = with out codify
          const hash = CryptoJS.SHA1(trankey_woc);
          var base64 = CryptoJS.enc.Base64.stringify(hash);

          var sesion = {
            "auth": {
              "login": login + "",
              "tranKey": base64 + "",
              "nonce": nonceB64 + "",
              "seed": seed2 + ""
                  },
                  "payment":{},
                }
                return(sesion)
            }

            function generarUUID() {
              let uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                const r = (Math.random() * 16) | 0,
                  v = c === 'x' ? r : (r & 0x3) | 0x8;
                return v.toString(16);
              });
              return uuid;
            }
        </script>
    @endsection

</x-app-layout>