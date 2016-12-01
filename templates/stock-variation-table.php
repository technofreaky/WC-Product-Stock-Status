<table class="stock-variation-listing">
    <thead>
        <tr>
            <td>Name</td>
            <td>Status</td>
        </tr>
    </thead>
    
    <tbody>
    <?php
        
        foreach($args as $arr){
            $text = empty($arr['availability']) ? '' : '<p class="stock '.esc_attr($arr['class']).'">'.esc_html($arr['availability']).'</p>'; 
            echo '<tr>';
            echo '<td>'.$arr['title'].'</td>';
            echo '<td>'.$text.'</td>';
            echo '</tr>'; 
        }
        
    ?>
    
    </tbody>
    
    
    <tfoot>
        <tr>
            <td>Name</td>
            <td>Status</td>
        </tr>
    </tfoot>
</table>