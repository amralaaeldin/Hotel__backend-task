
class serve
{
  private $xml_data = null;

  function __construct($format)
  {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: *");

    if ($format === 'json') {
      $json_data = json_encode(handling_resources::$data);
      print_r($json_data);
    }

    if ($format === 'xml') {
      $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
      handling_resources::array_to_xml(handling_resources::$data, $this->xml_data);
      print_r($this->xml_data->asXML());
    }
  }
}
