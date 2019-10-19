/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package playrollsystemusinginterface;

/**
 *
 * @author Radi
 */
public class payrollSystemTest {
    public static void main(String[] args) {
        SelariedEmployee se=new SelariedEmployee("Jubair Al Mahmud", "Rahat", "35df589", 35800);
        HourlyEmployee he=new HourlyEmployee("Abdul Alim", "Babu", "ff5891021", 2564, 200,7);
        System.out.println("Weekly employee salary informaiton : \n");
        System.out.println(se.toString());
        System.out.println("Weekly salary :"+se.earningAmount());
        
        System.out.println("\n\nHourly employee salary informaiton : \n");
        he.earningAmount();
        System.out.println(he.toString());
    }
}
