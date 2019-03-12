<?php
/**
 * Export data to excel
 *
 * $objPHPExcel->getActiveSheet()->getStyle('A1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
 * 
 * 
 * @author DAI
 * @since 17/4/2015
 */

namespace Box\Spout;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\Style;

final class Export
{    
    /**
     * @var string
     */
    private $_sFileName = 'Report';
    
    /**
     * @var string
     */
    private $_sTitle    = '';
    
    /**
     * @var array
     */
    private $_arrHeader = array();
    
    /**
     * @var array
     */
    private $_arrFieldDisplay = array();    
    
    /**
     * @var array
     */
    private $_arrData   = array();
    
    
    /**
     * Make up cell data
     * 
     * @var array
     */
    private $_customizeData = array();
    
    
    /**
     * Constructor
     * 
     * @param string $filename File name of report
     */
    public function __construct($filename = null)
    {
        if ($filename !== null) {
            $this->_sFileName = $filename;
        }
    }
    
    
    /**
     * Set report file name
     *
     * @param string $filename
     * @return \PHPExcel\Export
     */
    public function setFileName($filename)
    {
        $this->_sFileName = $filename;
        return $this;
    }
    
    
    /**
     * Set title of Excel report
     * 
     * @param string $title Report title
     * @return \PHPExcel\Export
     */
    public function setTitle($title)
    {
        $this->_sTitle = $title;
        return $this;
    }
    
    
    /**
     * Set header of Report
     * 
     * @param array $header Table Header Report
     * @return \PHPExcel\Export
     */
    public function setHeader(array $header)
    {
        $this->_arrHeader = $header;
        return $this;
    }
    
    
    /**
     * Set field display in report
     * 
     * @param array $displayField Field will display in report
     * @return \PHPExcel\Export
     */
    public function setFieldDisplay(array $displayField)
    {
        $this->_arrFieldDisplay = $displayField;
        return $this;
    }
    
    
    /**
     * Set data of Report
     *
     * @param array $header Table Header Report
     * @return \PHPExcel\Export
     */
    public function setData($data)
    {
        $this->_arrData = $data;
        return $this;
    }
    
    
    /**
     * Customize cell format
     * 
     * @param array $customize Array of callback function.
     * @return \PHPExcel\Export
     */
    public function customizeData(array $customize)
    {
        $this->_customizeData = $customize;
        return $this;
    }
    
    
    /**
     * Save and download file
     */
    public function save()
    {        
        $tmpFile = UPLOAD_TEMP . '/' . uniqid(time() * rand(1, 999)) . '.xlsx';
        
        $oPhpExcel = WriterFactory::create(Type::XLSX); // for XLSX files
        $oPhpExcel->openToFile($tmpFile);
        
        $boder = (new BorderBuilder())
                ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->build();
        
        $styleHeader = (new StyleBuilder())
                       ->setFontBold()
                       ->setFontSize(11)
                       ->setBorder($boder)
                       ->setBackgroundColor(Color::rgb(150, 150, 150))
                       ->build();
        
        $styleRow = (new StyleBuilder())
                    ->setBorder($boder)
                    ->build();

        // Set title
        $styleTitle = (new StyleBuilder())
                      ->setFontBold()
                      ->setFontSize(16)
                      ->build();
        $oPhpExcel->addRowWithStyle(array($this->_sTitle), $styleTitle);// fontsize 16
        $oPhpExcel->addRow(array(''));
        
        //set header
        $oPhpExcel->addRowWithStyle($this->_arrHeader, $styleHeader);
        
        if ($this->_arrData instanceof \Zend\Paginator\Paginator)
        {
//            dd("111");
            $this->_arrData->setItemCountPerPage(10000);
            $arrPage = $this->_arrData->getPages();

            for($page = 1; $page <= $arrPage->last; $page++)
            {
                $pData = $this->_arrData->getItemsByPage($page);
                
                foreach ($pData as $i => $arrData)
                {
                    $this->buildRow($oPhpExcel, $this->_arrFieldDisplay, $arrData, $styleRow);
                }
            }
        }
        else
        {
            $perPage =1000;
            $pageTotal = ceil((float)($this->_arrData->count()/$perPage));
//            dd((int)$pageTotal);
            for($page = 1; $page <= (int)$pageTotal; $page++){
                $pData = $this->_arrData->forPage($page,$perPage);

                foreach ($pData as $i => $arrData)
                {
                    $this->buildRow($oPhpExcel, $this->_arrFieldDisplay, $arrData, $styleRow);
                }
            }

        }
        
        $oPhpExcel->close();
        
        // Download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->_sFileName.'_'.date('dMy').'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tmpFile));
        readfile($tmpFile);
        
        @unlink($tmpFile);
        exit();
    }
    
    
    /**
     * Build row data
     * 
     * @param Box\Spout\Writer\XLSX\Writer $oPhpExcel
     * @param array $arrFields
     * @param array $arrData
     * @param StyleBuilder $oStyle
     */
    protected function buildRow(\Box\Spout\Writer\XLSX\Writer &$oPhpExcel, $arrFields, $arrData, Style $oStyle)
    {
        $arrRow = array();
    
        foreach ($arrFields as $i => $field)
        {
            // customize data format
            if (isset($this->_customizeData[$field]) AND is_callable($this->_customizeData[$field]))
            {
                $val = call_user_func($this->_customizeData[$field], $arrData[$field]);                
            }
            else
            {
                $val = isset($arrData[$field]) ? $arrData[$field] : '';
            }
            
            $arrRow[] = $val;
        }
        
        $oPhpExcel->addRowWithStyle($arrRow, $oStyle);
    }
}

// End of file
// Location: vendor/spout/src/Box/Spout/Export.php