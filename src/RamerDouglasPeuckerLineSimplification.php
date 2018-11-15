<?php


namespace IGCParser;


use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;

class RamerDouglasPeuckerLineSimplification
{

    // Inspired by https://rosettacode.org/wiki/Ramer-Douglas-Peucker_line_simplification#C.2B.2B


    private function PerpendicularDistance($currentPoint, $lineStartPoint, $lineEndPoint)
    {
        $dx = $lineEndPoint->first - $lineStartPoint->first;
        $dy = $lineEndPoint->second - $lineStartPoint->second;
     
        //Normalise
        $mag = pow(pow($dx,2.0)+pow($dy,2.0),0.5);

        if($mag > 0.0)
        {
            $dx /= $mag; $dy /= $mag;
        }
    
        $pvx = $currentPoint->first - $lineStartPoint->first;
        $pvy = $currentPoint->second - $lineStartPoint->second;
     
        //Get dot product (project pv onto normalized direction)
        $pvdot = $dx * $pvx + $dy * $pvy;
     
        //Scale line direction vector
        $dsx = $pvdot * $dx;
        $dsy = $pvdot * $dy;
     
        //Subtract this from pv
        $ax = $pvx - $dsx;
        $ay = $pvy - $dsy;
     
        return pow(pow($ax,2.0)+pow($ay,2.0),0.5);
    }
 
    public function RamerDouglasPeucker($pointList, $epsilon, $out)
    {
        if(count($pointList) <2 )
            throw new InvalidArgumentException("Not enough points to simplify");

        // Find the point with the maximum distance from line between start and end
        $dmax = 0.0;
        $index = 0;
        $end = count($pointList)-1;
        for($i = 1; $i < $end; $i++)
        {
            $d = $this->PerpendicularDistance($pointList[$i], $pointList[0], $pointList[$end]);
            if ($d > $dmax)
            {
                $index = $i;
                $dmax = $d;
            }
        }

        // If max distance is greater than epsilon, recursively simplify
        if($dmax > $epsilon)
        {
            // Recursive call
            $recResults1;
            $recResults2;
            //$firstLine($pointList.begin(), $pointList.begin()+$index+1);
            //$lastLine($pointList.begin()+$index, $pointList.end());
            $this->RamerDouglasPeucker($firstLine, $epsilon, $recResults1);
            $this->RamerDouglasPeucker($lastLine, $epsilon, $recResults2);

            // Build the result list
            //out.assign(recResults1.begin(), recResults1.end()-1);
            //out.insert(out.end(), recResults2.begin(), recResults2.end());
            if(count($out) < 2)
                throw new RuntimeException("Problem assembling output");
        }
        else
        {
            //Just return start and end points
//            out.clear();
//            out.push_back(pointList[0]);
//            out.push_back(pointList[end]);
        }
    }

}