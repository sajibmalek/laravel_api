<html>
 
<body>
<b> Base Url <b>http://127.0.0.1:8000/api/</b> 
  <br>
<table>
  <th>Method</th>
   <th>Endpoint</th>
   <th>Json Body</th>
   <th>response</th>
  <tr>
    <td>POST</td>
      <td>register</td>
    <td>
    {
   "firstName":"Sajib",
   "lastName":"Malek",
   "email":"a.malek0317@gmail.com",
   "mobile":"017123483xx",
   "password":"12345"
      }
    </td>
     <td>
       { 
       "status":"success", 
       "message":"User created successfully"
       }
     </td>

    
    
  </tr>

  <tr>
     <td>POST</td>
      <td>login</td>
    <td>
    {
   "email":"a.malek0317@gmail.com",
   "password":"12345"
      }
    </td>
     <td>
       { 
       "status":"success", 
       "message":"User login successfully"
       }
     </td>
  </tr>

  
</table>
  
</body>

  
</html>
