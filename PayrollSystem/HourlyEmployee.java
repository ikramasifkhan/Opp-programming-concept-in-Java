/*
 * In the name of Allah the benificent the merciful
* This class is child class of Employee
 */
package PayrollSystem;

/**
 *
 * @author Radi
 */
public class HourlyEmployee extends Employee{
    private int wage;
    private int hours;
    
    public HourlyEmployee(int wage, int hours, String firstName, String lastName, int socialSecurityNumber) {
        super(firstName, lastName, socialSecurityNumber);
        this.wage = wage;
        this.hours = hours;
    }

    @Override
    public int earningAmount(){
       return super.earningAmount()+wage*hours;
    }

    @Override
    public String toString() {
        int salaryAmount=earningAmount();
        return super.toString()+"\nHourly  Amount " + salaryAmount;
    }
    
}
