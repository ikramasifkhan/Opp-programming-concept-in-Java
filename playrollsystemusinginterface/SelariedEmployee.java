package playrollsystemusinginterface;

/**
 *
 * @author Radi
 */
public class SelariedEmployee implements Employee{
    private String firstName;
    private String lastName;
    private String socialSecurityNumber;
    private int weeklySalary;
    public SelariedEmployee(String firstName, String lastName, String socialSecurityNumber, int weeklySalary) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.socialSecurityNumber = socialSecurityNumber;
        this.weeklySalary=weeklySalary;
    }
    
    @Override
    public double earningAmount(){
        double salaryAmount;
        salaryAmount=weeklySalary*4+amount;
        return salaryAmount;
    }
    @Override
    public String toString(){
         String employeeInfo=
                 "First name:"+" "+firstName
                 +"\nLast name:"+" "+lastName
                 +"\nSSN number: "+" "+socialSecurityNumber;
                 //+"\nWeekly amount"+" "+this.earningAmount();
         return employeeInfo;
    }
}
