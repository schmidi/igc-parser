<?php


namespace IGCParser;


class RDPLineSimplification
{

    // Inspired by https://rosettacode.org/wiki/Ramer-Douglas-Peucker_line_simplification#C.2B.2B


    private static function PerpendicularDistance($ptX, $ptY, $l1X, $l1Y, $l2X, $l2Y)
    {
        $dx = $l2X - $l1X;
        $dy = $l2Y - $l1Y;
     
        //Normalise
        $mag = pow(pow($dx,2.0)+pow($dy,2.0),0.5);

        if($mag > 0.0)
        {
            $dx /= $mag; $dy /= $mag;
        }
    
        $pvx = $ptX - $l1X;
        $pvy = $ptY - $l1Y;
     
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
 
    public static function RDPLineSimplification($pointList, $epsilon)
    {

        if($epsilon <= 0) {
            throw new \InvalidArgumentException("Non-positive epsilon");
        }

        if(count($pointList) <2 ) {
            return $pointList;
        }


        // Find the point with the maximum distance from line between start and end
        $dMax = 0.0;
        $index = 0;
        $end = count($pointList)-1;
        for($i = 1; $i < $end; $i++)
        {
            $d = self::PerpendicularDistance(
                $pointList[$i]->latitude,
                $pointList[$i]->longitude,
                $pointList[0]->latitude,
                $pointList[0]->longitude,
                $pointList[$end]->latitude,
                $pointList[$end]->longitude
            );

            if ($d > $dMax)
            {
                $index = $i;
                $dMax = $d;
            }
        }

        // If max distance is greater than epsilon, recursively simplify
        if($dMax > $epsilon)
        {
            // Recursive call

            $recResults1 = self::RDPLineSimplification(array_slice($pointList, 0, $index+1), $epsilon));
            $recResults2 = self::RDPLineSimplification(array_slice($pointList, $index, $end + $index+1), $epsilon));

            $resultList = array_merge(
                array_slice($recResults1, 0, count($recResults1) - 1),
                array_slice($recResults2, 0, count($recResults2) -1)
                );

        }
        else
        {
            $resultList = array($pointList[0], $pointList[$end]);
        }

        return $resultList;
    }

}