/*
 * In the name of Allah the benificent the merciful
* This class is child class of Employee
 */
package PayrollSystem;
/**
 * @author Radi
 */
public class SalariedEmployee extends Employee{
    private int weeklySalary;
    public int salaryAmount;
    public SalariedEmployee(String firstName, String lastName, int socialSecurityNumber, int weeklySalary) {
        super(firstName, lastName, socialSecurityNumber);
        this.weeklySalary=weeklySalary;
    }
    
    @Override
    public int earningAmount(){
        int salaryAmount =super.earningAmount()+weeklySalary*4;
        return salaryAmount;
    }

    @Override
    public String toString() {
        return super.toString()+"\nWeeklySalary Amount "+this.earningAmount();
    }
    
}
