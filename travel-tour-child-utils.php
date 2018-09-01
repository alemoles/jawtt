<?php
if( !function_exists('travel_tour_child_validate_profile_field') ){
    function travel_tour_child_validate_profile_field( $fields ){

        $error = new WP_ERROR();

        foreach( $fields as $slug => $field ){
            $error_message = $error->get_error_message('1');
            if( !empty($field['required']) && empty($_POST[$slug]) && empty($error_message) ){
                $error->add('1', esc_html__('Please fill all required fields.', 'propietario'));
            }

            if( !empty($field['type']) && $field['type'] == 'email' && !is_email($_POST[$slug]) ){
                $error->add('2', esc_html__('Incorrect email address.', 'propietario'));
            }
        }

        $error_message = $error->get_error_message();
        if( !empty($error_message) ){
            return $error;
        }else{
            return true;
        }

    } // tourmaster_validate_profile_field
}

if (!function_exists('travel_tour_get_provincia_list')) {
    function travel_tour_get_provincias_list()
    {
        $ret = array(
            array(
                'pinar',
                'Pinar del Río',
            ),
            array(
                'habana',
                'La Habana'
            ),
            array(
                'artemisa',
                'Artemisa'
            ),
            array(
                'mayabeque',
                'Mayabeque'
            ),
            array(
                'matanzas',
                'Matanzas'
            ),
            array(
                'vc',
                'Villa Clara'
            ),
            array(
                'cfg',
                'Cienfuegos'
            ),
            array(
                'ss',
                'Sancti Spíritus'
            ),
            array(
                'cav',
                'Ciego de Ávila'
            ),
            array(
                'cmg',
                'Camagüey'
            ),
            array(
                'lt',
                'Las Tunas'
            ),
            array(
                'hol',
                'Holguín'
            ),
            array(
                'grm',
                'Granma'
            ),
            array(
                'stg',
                'Santiago de Cuba'
            ),
            array(
                'gtm',
                'Guantánamo'
            ),
            array(
                'isla',
                'Isla de la Juventud'
            ),
        );
        return $ret;
    }
}

if (!function_exists('travel_tour_get_municipios_list')) {
    function travel_tour_get_municipios_list()
    {
        $ret = array(
            "pinar" => array(
                'Consolación del Sur', 'Guane', 'La Palma', 'Los Palacios', 'Mantua', 'Minas de Matahambre', 'Pinar del Río',
                'San Juan y Martínez', 'San Luis', 'Sandino', 'Viñales'
            ),
            "habana" => array(
                'Arroyo Naranjo', 'Boyeros', 'Centro Habana', 'Cerro', 'Cotorro', 'Diez de Octubre', 'Guanabacoa', 'Habana del Este',
                'Habana Vieja', 'La Lisa', 'Marianao', 'Playa', 'Plaza', 'Regla', 'San Miguel del Padrón'
            ),
            "artemisa" => array(
                'Alquízar', 'Artemisa', 'Bauta', 'Caimito', 'Guanajay', 'Güira de Melena', 'Mariel', 'San Antonio de los Baños',
                'Bahía Honda', 'San Cristóbal', 'Candelaria'
            ),
            "mayabeque" => array(
                'Batabanó', 'Bejucal', 'Güines', 'Jaruco', 'Madruga', 'Melena del Sur', 'Nueva Paz', 'Quivicán',
                'San José de las Lajas', 'San Nicolás de Bari', 'Santa Cruz del Norte'
            ),
            "matanzas" => array(
                'Calimete', 'Cárdenas', 'Ciénaga de Zapata', 'Colón', 'Jagüey Grande', 'Jovellanos', 'Limonar',
                'Los Arabos', 'Martí', 'Matanzas', 'Pedro Betancourt', 'Perico', 'Unión de Reyes'
            ),
            "vc" => array(
                'Caibarién', 'Camajuaní', 'Cifuentes', 'Corralillo', 'Encrucijada', 'Manicaragua', 'Placetas',
                'Quemado de Güines', 'Ranchuelo', 'Remedios', 'Sagua la Grande', 'Santa Clara', 'Santo Domingo'
            ),
            "cfg" => array(
                'Abreus', 'Aguada de Pasajeros', 'Cienfuegos', 'Cruces', 'Cumanayagua', 'Palmira', 'Rodas',
                'Santa Isabel de las Lajas'
            ),
            "ss" => array(
                'Cabaigúan', 'Fomento', 'Jatibonico', 'La Sierpe', 'Sancti Spíritus', 'Taguasco', 'Trinidad', 'Yaguajay'
            ),
            "cav" => array(
                'Ciro Redondo', 'Baraguá', 'Bolivia', 'Chambas', 'Ciego de Ávila', 'Florencia', 'Majagua', 'Morón',
                'Primero de Enero', 'Venezuela'
            ),
            "cmg" => array(
                'Camagüey', 'Carlos Manuel de Céspedes', 'Esmeralda', 'Florida', 'Guaimaro', 'Jimagüayú', 'Minas', 'Najasa',
                'Nuevitas', 'Santa Cruz del Sur', 'Sibanicú', 'Sierra de Cubitas', 'Vertientes'
            ),
            "lt" => array(
                'Amancio Rodríguez', 'Colombia', 'Jesús Menéndez', 'Jobabo', 'Las Tunas', 'Majibacoa', 'Manatí', 'Puerto Padre'
            ),
            "hol" => array(
                'Antilla', 'Báguanos', 'Banes', 'Cacocum', 'Calixto García', 'Cueto', 'Frank País', 'Gibara', 'Holguín',
                'Mayarí', 'Moa', 'Rafael Freyre', 'Sagua de Tánamo', 'Urbano Noris'
            ),
            "grm" => array(
                'Bartolomé Masó', 'Bayamo', 'Buey Arriba', 'Campechuela', 'Cauto Cristo', 'Guisa', 'Jiguaní', 'Manzanillo',
                'Media Luna', 'Niquero', 'Pilón', 'Río Cauto', 'Yara'
            ),
            "stg" => array(
                'Contramaestre', 'Guamá', 'Julio Antonio Mella', 'Palma Soriano', 'San Luis', 'Santiago de Cuba',
                'Segundo Frente', 'Songo la Maya', 'Tercer Frente'
            ),
            "gtm" => array(
                'Baracoa', 'Caimanera', 'El Salvador', 'Guantánamo', 'Imías', 'Maisí', 'Manuel Tames', 'Niceto Pérez',
                'San Antonio del Sur', 'Yateras'
            ),
            "isla" => array(
                'Isla de la Juventud'
            )
        );
        return $ret;
    }
}