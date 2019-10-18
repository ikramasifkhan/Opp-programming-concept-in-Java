/*
 * In the name of Allah the benificent the merciful
 */
package PayrollSystem;

/**
 * @author Radi
 */
public  class Employee {

    private String firstName;
    private String lastName;
    private int socialSecurityNumber;
    private final int amount = 50000;

    public int amountInfo;
    public Employee(String firstName, String lastName, int socialSecurityNumber) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.socialSecurityNumber = socialSecurityNumber;
    }

    public int earningAmount() {
        amountInfo = this.amount;
        return amountInfo;
    }

    @Override
    public String toString() {

        String employeeInformation
                = "Employee first name : " + firstName
                + "\nEmployee first name : " + lastName
                + "\nSocial security number : " + socialSecurityNumber
                + "\n Amount " + this.amountInfo;
        return employeeInformation;
    }
}
