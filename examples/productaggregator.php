<?php
/**
 * Grab some The Dead Weather albums from Freebase
 */
require(__DIR__ . '/../bootstrap.php');

$fields = array("Handle","Title","Body (HTML)","Vendor","Type","Tags","Published","Option1 Name","Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","Variant SKU","Variant Grams","Variant Inventory Tracker","Variant Inventory Qty","Variant Inventory Policy","Variant Fulfillment Service","Variant Price","Variant Compare At Price","Variant Requires Shipping","Variant Taxable","Variant Barcode","Image Src","Image Alt Text","Gift Card");
//$api_key = 'Your API KEY hERE';

$uri = "http://api.bigcartel.com/efcollection/products.json";
$file = fopen("products.csv", "w");
fputcsv ($file, $fields);
$response = \Httpful\Request::get($uri)
    ->expectsJson()
    ->sendIt();

$entities = $response->body;


foreach($entities as $entity)
{	
 
 $options = $entity->options;
 $images = $entity->images;

$option_count =  count($options);

$image_count = count($images);

$count = max($option_count,$image_count);

//print $count;
//print $images[0]->url;

 for($i=0; $i<$count; $i++)
    {
          if(isset($entity->permalink))
              {
                $handle =  $entity->permalink;
              }
          else
              {
                $handle = "";
              }
          if(isset($entity->name))
            {
               $title =  $entity->name;
            }
          else
            {
              $title = "";
            }
           if(isset($entity->description))
            {
               $body =  $entity->description;
            }
          else
            {
              $body = "";
            } 
           
            $vendor = "EF Collection";
          
          $categories = $entity->categories;
          $cnt = count($categories);
          $types = "";
          $tags = "";
          for($j = 0; $j<$cnt; $j++)
          {
            if($j != $cnt-1)
            {  
                  $types = $types.$categories[$j]->permalink.",";
                  $tags = $tags.$categories[$j]->name.",";
            }
            else if($j == $cnt-1)
            {
                 $types = $types.$categories[$j]->permalink;
                  $tags = $tags.$categories[$j]->name;
            }
          }
          
          $published = "TRUE";

          $option1_name = "Types";

           if(isset($options[$i]))
            {
               $option1_value = $options[$i]->name;
            }
          else
            {
              $option1_value = "";
            } 
            $option2_name = "";
            $option2_value = "";
            $option3_name = "";
            $option3_value = "";
            $vsku = "";
            $vg = "0";
            $vit = "";
            $vq = "1";
            $vip = "continue";
            $vfs = "manual";
             if(isset($options[$i]))
            {
               $price = $options[$i]->price;
            }
          else
            {
              $price = "";
            } 
             
            $vct = "";
            $vrs = "TRUE";
            $vtax = "FALSE";
            $vbar = "";

           if(isset($images[$i]))
            {
               $img = $images[$i]->url;
            }
          else
            {
              $img = "";
            }  
            $gf = "FALSE";
  
   $values = array($handle,$title,$body,$vendor,$types, $tags, $published, $option1_name, $option1_value, $option2_name, $option2_value, $option3_name, $option3_value, $vsku, $vg, $vit, $vq, $vip, $vfs, $price, $vct, $vrs, $vtax, $vbar, $img, $gf);

    $csv = array();
  foreach ($values as $value)
   {
        
        $csv[] = $value;
   }    
        fputcsv ($file, $csv);
   

    }

}
fclose($file);
echo ("Final Output in productss.csv in this same directory");

?>