
package StudentManagementSystem;

/**
 * @author Radi
 */
public class Staff extends Employee{
    private String title;
    public Staff(String name, String address, String phoneNumber, String emailAddress, String officeAddress, int salary,String title) {
        super(name, address, phoneNumber, emailAddress, officeAddress, salary);
        this.title=title;
    }
    
    @Override
    public String toString(){
        String staffInfo=
                "Staff name :"+name
                +"\n Staff address:"+address
                +"\n Office address:"+officeAddress
                +"\nContact number :"+phoneNumber
                +"\nEmail address :"+emailAddress
                +"\n Salary"+ salary
                +"\n Title"+title;
        return staffInfo;
    }
    
}
