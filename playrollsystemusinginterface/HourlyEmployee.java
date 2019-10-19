package playrollsystemusinginterface;

/**
 *
 * @author Radi
 */
public class HourlyEmployee extends SelariedEmployee {
    private int wage;
    private int hours;

    public HourlyEmployee(String firstName, String lastName, String socialSecurityNumber, int weeklySalary, int wage, int hours) {
        super(firstName, lastName, socialSecurityNumber, weeklySalary);
        this.wage = wage;
        this.hours = hours;
    }

    @Override
    public double earningAmount() {
        double salaryAmount=wage*hours+amount;
        return salaryAmount;
    }
    
    @Override
    public String toString(){
        String employeeInfo=
        super.toString()+"\nHourly salary: "+" "+this.earningAmount();
        return employeeInfo;
    }
}
