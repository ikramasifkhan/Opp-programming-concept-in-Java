/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package PayrollSystem;

/**
 *
 * @author Radi
 */
public class PayrollSystemTest {
    public static void main(String[] args) {
        Employee em =new Employee("Rahat", "Khan", 1564);
       SalariedEmployee se= new SalariedEmployee("Takbir", "Khan", 1564, 100);
        HourlyEmployee he=new HourlyEmployee(3,3,"Sujon", "Sarkar", 1564);
        em.earningAmount();
        System.out.println("Employee infomation");
        System.out.println(em.toString());
        se.earningAmount();
        System.out.println("\n\nEmployee weekly infomation");
        System.out.println(se.toString());
        he.earningAmount();
        System.out.println("\n\nEmployee hourly infomation");
        System.out.println(he.toString());
    }
}
