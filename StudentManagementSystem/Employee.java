package StudentManagementSystem;

/**
 * @author Radi
 */
public class Employee extends Person{
    public String officeAddress;
    public int salary;
    
    public Employee(String name, String address, String phoneNumber, String emailAddress, String officeAddress, int salary) {
        super(name, address, phoneNumber, emailAddress);
         this.officeAddress=officeAddress;
        this.salary=salary;
    }
    
    @Override
    public String toString(){
        MyDate mydate=new MyDate(10, "January", 2007);
        String hirringDate= mydate.hirringDate();
        String employeeInfo=
                "Employee name :"+name
                +"\nEmployee address:"+address
                +"\n Office address:"+officeAddress
                +"\nContact number :"+phoneNumber
                +"\nEmail address :"+emailAddress
                +"\n Salary"+ salary
                +"\n Hiring date"+hirringDate;
        return employeeInfo;
    }
}
