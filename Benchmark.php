<?php

class Benchmark
{
    
    public $starttime = 0;
    public $lastmarktime = false;
    public $lastmemory = false;
    public $out = array();
    
    protected function convert($size)
    {
        $unit = array(
            'b',
            'kb',
            'mb',
            'gb',
            'tb',
            'pb'
        );
        return @number_format($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }
    
    public function marker($label)
    {
        $newtime = microtime(true);

        $label = str_replace('"', '\\"', $label);
        
        $totaltime = number_format(round(($newtime - $this->starttime) * 1000, 2), 2);
        if (!!$this->lastmarktime) {
            $sincetime = number_format(round(($newtime - $this->lastmarktime) * 1000, 2), 2);
        } else {
            $sincetime = "0";
        }
        $thismemory = memory_get_usage(true);
        if (!!$this->lastmemory) {
            $minusmem = $thismemory - $this->lastmemory;
            if ($minusmem > 0) {
                $sincememory = +$thismemory - +$this->lastmemory;
            } else {
                $sincememory = "0";
            }
        } else {
            $sincememory = "0";
        }
        $this->out[$label] = array(
            'time_this' => $sincetime,
            'time_total' => $totaltime,
            'mem_this' => $sincememory,
            'mem_total' => $thismemory
        );
        
        $this->lastmarktime = microtime(true);
        $this->lastmemory   = $thismemory;
    }
    
    public function outputText()
    {
        $output = '';
        foreach ($this->out as $k => $v) {
            $output .= str_pad($k, 20, " ", STR_PAD_LEFT) . ":\t ";
            $output .= str_pad($v['time_this'], 13, " ", STR_PAD_LEFT) . "\t";
            $output .= str_pad($v['time_total'], 13, " ", STR_PAD_LEFT) . "\t";
            $output .= str_pad($v['mem_this'], 13, " ", STR_PAD_LEFT) . "\n";
            $output .= str_pad($v['mem_total'], 13, " ", STR_PAD_LEFT) . "\t";
        }
        return $output;
    }
    
    public function outputHTML()
    {
        $output = '';
        $output = '<table class="simplebenchmark">' . "\n";
        $output .= '<thead>' . "\n";
        $output .= '<tr>';
        $output .= '<th>Label</th>';
        $output .= '<th>This Time</th>';
        $output .= '<th>Total Time</th>';
        $output .= '<th>This Memory</th>';
        $output .= '<th>Total Memory</th>';
        $output .= '</tr>' . "\n";
        $output .= '</thead>' . "\n";
        
        $output .= '<tbody>' . "\n";
        foreach ($this->out as $k => $v) {
            $output .= '<tr>';
            $output .= '<td>' . $k . '</td>';
            $output .= '<td>' . $v['time_this'] . '</td>';
            $output .= '<td>' . $v['time_total'] . '</td>';
            $output .= '<td>' . $v['mem_this'] . '</td>';
            $output .= '<td>' . $v['mem_total'] . '</td>';
            $output .= '</tr>' . "\n";
        }
        $output .= '</tbody>' . "\n";
        
        $output .= '</table>' . "\n";
        return $output;
    }
    
    public function outputConsoleLog($scripttags = true)
    {
        $outputText = $this->outputText();
        $outputText = str_replace("\n", '\n', $outputText);
        $output     = '';
        if ($scripttags)
            $output .= '<script>' . "\n";
        $output .= 'console.log("' . $outputText . '");' . "\n";
        if ($scripttags)
            $output .= '</script>' . "\n";
        return $output;
    }
    
    public function outputConsoleTable($scripttags = true)
    {
        $output = '';
        
        $outarray = array();
        
        if ($scripttags)
            $output .= '<script>' . "\n";

        $output .= 'console.table([';

        if(count($this->out)) {

            foreach ($this->out as $k => $v) {

                $thisout = '{';
                    $thisout.= 'Marker: "' . $k . '", ';
                    $thisout.= 'This_Time: ' . $v['time_this'] . ', ';
                    $thisout.= 'Total_Time: ' . $v['time_total'] . ', ';
                    $thisout.= 'This_Mem: ' . $v['mem_this'] . ', ';
                    $thisout.= 'Total_Mem: ' . $v['mem_total'];
                $thisout.= '}';

                $outarray[] = $thisout;
            }
        }
        $output .= implode(',', $outarray);
        $output .= ']);';

        if ($scripttags)
            $output .= '</script>' . "\n";

        return $output;
    }
    
    public function __construct($starttime = false)
    {
        if (!$starttime)
            $starttime = microtime(1);
        $this->starttime = $starttime;
    }
}
