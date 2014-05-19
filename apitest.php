<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>ApiTest</title>
    </head>
    <body>
        <table>
            <tr>
                <th>Query</th>
                <th>Submit</th>
            </tr>
            <tr>
                <form action="api.php?/login" method="post">
                    <td>/login <input type="text" name="name" value="name" /><input type="password" name="pw" value="pw"></td>
                    <td><input type="submit" />
                </form>
            </tr>
			<tr>
				<form action="api.php?/get/locations" method="post">
					<td>/get/locations <input type="text" name="sid" value="sid" /></td><input type="hidden" name="type_id" value="2" />
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/get/location" method="post">
					<td>/get/location <input type="text" name="sid" value="sid" /></td><input type="hidden" name="id" value="2" />
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/get/location_types" method="post">
					<td>/get/location_types <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/get/userlist/1" method="post">
					<td>/get/userlist/&lt;location_id(1)&gt; <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/get/user/1" method="post">
					<td>/get/user/&lt;userid(1)&gt; <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/setstatus/Just%20chillin%27%20my%20ass" method="post">
					<td>/setstatus/&lt;status(Just chillin&#39; my ass)&gt; <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/checkin/1/150/250" method="post">
					<td>/checkin/&lt;checkin(1/150/250)&gt; <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/checkout" method="post">
					<td>/checkout <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
			<tr>
				<form action="api.php?/halt" method="post">
					<td>/halt <input type="text" name="sid" value="sid" /></td>
                    <td><input type="submit" />
				</form>
			</tr>
        </table>
    </body>
</html>
