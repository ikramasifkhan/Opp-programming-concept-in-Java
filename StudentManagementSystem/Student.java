
package StudentManagementSystem;

/**
 *
 * @author Radi
 */
public class Student extends Person{
    
    public Student(String name, String address, String phoneNumber, String emailAddress) {
        super(name, address, phoneNumber, emailAddress);
             
    }
    @Override
    public String toString(){
        Status status =new Status(2);
        String studentStatus=status.showStatus();
        String studentInfo=
                "Student name :"+name
                +"\nStudent address:"+address
                +"\nContact number :"+phoneNumber
                +"\nEmail address :"+emailAddress
                +"\n Student status: "+studentStatus;
        return studentInfo;
    }
}
