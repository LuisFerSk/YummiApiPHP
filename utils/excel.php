<?php
class Excel
{
}
require_once 'conn.php';

$output = "";

if (isset($_POST['export'])) {
    $output .= "
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Direccion</th>
					</tr>
				<tbody>
		";

    while ($fetch = []) {

        $output .= "
					<tr>
						<td>" . $fetch['mem_id'] . "</td>
						<td>" . $fetch['firstname'] . "</td>
						<td>" . $fetch['lastname'] . "</td>
						<td>" . $fetch['address'] . "</td>
					</tr>
		";
    }

    $output .= "
				</tbody>
				
			</table>
		";

    echo $output;
}
